<?php

namespace Koupon\Api;

use \Exception;
use Koupon\Api\Log;
use \DateTimeImmutable;

final class Cart
{
    private string $id;
    private float $total;
    private array $items;

    public $cart;

    public function __construct()
    {
        $this->id = session_id();
        $this->total = 0.0;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        Log::console("Adding item to cart" . json_encode($item), "info");
        $this->items[] = $item;
        $this->onAddItem($item);
    }

    private function onAddItem(CartItem $item): void
    {
        $this->total += $item->getPrice() * $item->getQuantity();

        $this->cart = [
            "id" => $this->getId(),
            "total" => $this->getTotal(),
            "items" => $this->getItems(),
            "created_at" => (new DateTimeImmutable())->format("Y-m-d H:i:s"),
            "updated_at" => (new DateTimeImmutable())->format("Y-m-d H:i:s")
        ];

        Log::console("Cart" . json_encode($this->cart), "info");
    }

    public function removeItem(string $itemId): void
    {
        $this->items = array_filter($this->items, function (CartItem $item) use ($itemId)
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
