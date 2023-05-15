<?php

namespace Koupon\Api;

use \Exception;
use Koupon\Api\Log;

final class CartItem
{
    public string $id;
    public string $name;
    public float $price;
    public int $quantity;

    public function __construct(string $id, string $name, float $price, int $quantity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
