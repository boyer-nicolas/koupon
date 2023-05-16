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
        $this->coupon = new Coupon(1, "FIRST20", 0.2, 1, new DateTimeImmutable(), new DateTimeImmutable());
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTotal(): float
    {
        $items = $this->getItems();
        $total = 0.0;
        foreach ($items as $cartItem)
        {
            $total += $cartItem->getPrice() * $cartItem->getQuantity();
        }
        $total = round($total, 2);

        return $total;
    }

    public function get(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    public function addCoupon(string $code): void
    {
        $this->validateCoupon($code);

        $this->coupon->applyToCart($this);
    }

    private function validateCoupon(string $code): bool
    {
        return $this->coupon->validate($code, $this);
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

    private function onAddItem(): void
    {
        $_SESSION['cart'] = [
            "id" => $this->getId(),
            "total" => $this->getTotal(),
            "items" => $this->getItems(),
            "created_at" => $this->getCreationDate(),
            "updated_at" => $this->getUpdateDate(),
            "reduction" => $this->getReduction(),
            "timesCouponApplied" => $this->getTimesCouponApplied(),
        ];
    }

    private function onRemoveItem(): void
    {
        $_SESSION['cart'] = [
            "id" => $this->getId(),
            "total" => $this->getTotal(),
            "items" => $this->getItems(),
            "created_at" => $this->getCreationDate(),
            "updated_at" => $this->getUpdateDate(),
            "reduction" => $this->getReduction(),
            "timesCouponApplied" => $this->getTimesCouponApplied(),
        ];
    }

    private function getCreationDate(): string
    {
        return $_SESSION['cart']['created_at'] ?? $this->setCreationDate(new DateTimeImmutable());
    }

    private function getUpdateDate(): string
    {
        return $_SESSION['cart']['updated_at'] ?? $this->setUpdateDate(new DateTimeImmutable());
    }

    private function setCreationDate(DateTimeImmutable $date): void
    {
        $_SESSION['cart']['created_at'] = (new DateTimeImmutable())->format("Y-m-d H:i:s");
    }

    private function setUpdateDate(DateTimeImmutable $date): void
    {
        $_SESSION['cart']['updated_at'] = (new DateTimeImmutable())->format("Y-m-d H:i:s");
    }

    public function getTimesCouponApplied(): int
    {
        return $_SESSION['cart']['timesCouponApplied'] ?? 0;
    }

    public function setTimesCouponApplied(int $timesCouponApplied): void
    {
        $_SESSION['cart']['timesCouponApplied'] = $timesCouponApplied;
    }

    public function setReduction(float $reduction): void
    {
        $_SESSION['cart']['reduction'] = $reduction;
    }

    public function getReduction(): float
    {
        return $_SESSION['cart']['reduction'] ?? 0.0;
    }

    public function removeItem(CartItem $item): void
    {
        $_SESSION['cart']['items'] = array_filter($_SESSION['cart']['items'], function ($cartItem) use ($item)
        {
            return $cartItem->getId() !== $item->getId();
        });

        $this->onRemoveItem($item);
    }

    public function applyCoupon(Coupon $coupon): void
    {
        $this->setReduction($coupon->getValue());
    }

    public function removeCoupon(Coupon $coupon): void
    {
        $this->total += $coupon->getValue();
    }
}
