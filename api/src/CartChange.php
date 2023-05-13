<?php

namespace Koupon\Api;

use \Exception;
use DateTime;
use Koupon\Api\Log;

class CartChange
{
    private array $cart = [];
    private array $items = [];
    private array $newCart = [];
    private array $newItems = [];

    private DateTime $date;

    public function __construct(array $items, array $cart)
    {
        $this->items = $items;
        $this->registerCartChange($items, $cart);
    }

    public function getItems()
    {
        return $this->items;
    }

    public function registerCartChange(array $items, array $cart)
    {
        $this->newItems[] = $items;
        $this->cart = $cart;
        $this->date = new DateTime('now');

        $this->newCart = [
            'date' => $this->date->format('Y-m-d H:i:s'),
            'items' => $this->items
        ];

        Log::console(print_r($this->newCart, true), 'error');

        return $this->newCart;
    }

    public function getCart()
    {
        return $this->newCart;
    }

    public function undoCartChange(array $items, array $cart)
    {
        $this->newItems = $items;
        $this->newCart = $cart;
        $this->date = new DateTime('now');

        $this->newCart = [
            'date' => $this->date->format('Y-m-d H:i:s'),
            'items' => $this->newItems
        ];

        return $this->newCart;
    }
}
