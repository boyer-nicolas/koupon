<?php

namespace Koupon\Api;

use \Exception;
use Koupon\Api\Log;
use \DateTimeImmutable;

final class Cart
{
    private string $id;
    private float $total;
    private array $items = [];

    public function __construct(string $id, float $total, array $items)
    {
        $this->id = $id;
        $this->total = $total;
        $this->items = $items;
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
