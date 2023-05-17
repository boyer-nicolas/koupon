<?php

namespace Koupon\Api;

use \Exception;
use Koupon\Api\Log;

final class Product
{
    private int $id;
    private string $title;
    private string $description;
    private string $image;
    private string $link;
    private array $tags;
    private float $price;
    private string $currency;
    private bool $new;
    private int $quantity;

    public function __construct(int $id, string $title, string $description, string $image, string $link, array $tags, float $price, string $currency, bool $new, int $quantity)
    {
        $this->setId($id);
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setImage($image);
        $this->setLink($link);
        $this->setTags($tags);
        $this->setPrice($price);
        $this->setCurrency($currency);
        $this->setNew($new);
        $this->setQuantity($quantity);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getNew(): bool
    {
        return $this->new;
    }

    public function setNew(bool $new): void
    {
        $this->new = $new;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
