<?php

namespace Koupon\Api;

use \Exception;
use Koupon\Api\CartChange;

final class Cart
{
    private $cart = [];

    public function __construct()
    {
        if (!isset($this->cart))
        {
            $this->cart = [];
        }
    }

    public function add()
    {
        try
        {
            $items = json_decode(file_get_contents('php://input'), true);

            return [new CartChange($items, $this->cart)];
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    public function onAdd(CartChange $event)
    {
        $this->cart = $event->getCart();

        return $this->cart;
    }
}
