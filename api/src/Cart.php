<?php

namespace Koupon\Api;

use \Exception;
use Koupon\Api\Log;
use Koupon\Api\Coupon;
use \DateTimeImmutable;

final class Cart
{
    private string $id;
    private float $total;

    public $cart;
    private Coupon $coupon;

    public function __construct()
    {
        $this->id = session_id();
        $this->total = 0.0;
        $this->coupon = new Coupon(1, "FIRST20", 0.1, 1, new DateTimeImmutable(), new DateTimeImmutable());
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function get(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    public function addCoupon(string $code): void
    {
        if ($code !== $this->coupon->getCode())
        {
            throw new Exception("Invalid coupon code");
        }

        $this->applyCoupon($this->coupon);

        $_SESSION['cart']['coupon'] = $this->coupon;
    }

    public function getItems(): array
    {
        return $_SESSION['cart']['items'] ?? [];
    }

    public function addItem(CartItem $item): void
    {
        // Check if item already exists in cart$
        if (isset($_SESSION['cart']['items']))
        {
            foreach ($_SESSION['cart']['items'] as $cartItem)
            {
                if ($cartItem->getId() === $item->getId())
                {
                    $cartItem->setQuantity($cartItem->getQuantity() + $item->getQuantity());

                    $this->onAddItem($cartItem);
                    return;
                }
            }
        }
        $_SESSION['cart']['items'][] = $item;
        $this->onAddItem($item);
    }

    private function onAddItem(CartItem $item): void
    {
        $currentTotal = $_SESSION['cart']['total'] ?? 0.0;
        $amountToAdd = floatval($item->getPrice()) * $item->getQuantity();
        $result = round($currentTotal += $amountToAdd, 2);

        $_SESSION['cart'] = [
            "id" => $this->getId(),
            "total" => $result,
            "items" => $this->getItems(),
            "created_at" => (new DateTimeImmutable())->format("Y-m-d H:i:s"),
            "updated_at" => (new DateTimeImmutable())->format("Y-m-d H:i:s")
        ];

        Log::console("Cart" . json_encode($_SESSION['cart']), "info");
    }

    public function removeItem(string $itemId): void
    {
        $_SESSION['cart']['items'] = array_filter($_SESSION['cart']['items'], function (CartItem $item) use ($itemId)
        {
            return $item->getId() !== $itemId;
        });
    }

    public function applyCoupon(Coupon $coupon): void
    {
        $this->total -= $coupon->getValue();
    }

    public function removeCoupon(Coupon $coupon): void
    {
        $this->total += $coupon->getValue();
    }
}
