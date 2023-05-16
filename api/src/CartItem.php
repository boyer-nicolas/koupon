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

    public string $link;
    public array $tags;
    public string $image;

    public function __construct(string $id, string $name, float $price, int $quantity, string $link = "", array $tags = [], string $image = "")
    {
        $this->setId($id);
        $this->setName($name);
        $this->setPrice($price);
        $this->setQuantity($quantity);
        $this->setLink($link);
        $this->setTags($tags);
        $this->setImage($image);
    }

    public function setLink(string $link = ""): void
    {
        $link = Filters::validateString($link);
        $this->link = $link;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setTags(array $tags = []): void
    {
        $tags = Filters::validateArray($tags);
        $this->tags = $tags;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setImage(string $image = ""): void
    {
        $image = Filters::validateString($image);
        $this->image = $image;
    }

    public function getImage(): string
    {
        return $this->image;
    }


    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name = ""): void
    {
        $name = Filters::validateString($name);
        if (strlen($name) < 1)
        {
            throw new Exception("Name must be at least 1 character");
        }
        $this->name = $name;
    }

    public function setId(string $id = ""): void
    {
        $id = Filters::validateString($id);
        if (strlen($id) < 1)
        {
            throw new Exception("Id must be at least 1 character");
        }
        $this->id = $id;
    }

    public function setPrice(float $price = 0.0): void
    {
        $price = Filters::validateFloat($price);
        if ($price < 0.0)
        {
            throw new Exception("Price must be greater than 0");
        }
        $this->price = $price;
    }

    public function getPrice(): float
    {
        return floatval(number_format($this->price, 2));
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        if ($quantity < 1)
        {
            throw new Exception("Quantity must be greater than 0");
        }
        $this->quantity = $quantity;
    }

    public function __toString(): string
    {
        return json_encode([
            "id" => $this->getId(),
            "name" => $this->getName(),
            "price" => $this->getPrice(),
            "quantity" => $this->getQuantity()
        ]);
    }
}
