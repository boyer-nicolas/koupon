<?php

namespace Koupon\Api;

use \Exception;

final class Cart
{
    private $cart = [];
    private $session;

    public function __construct()
    {
        $this->session = new Session();
        $this->cart = $this->session->get('cart');
        if (!isset($this->cart))
        {
            $this->cart = [];
            $this->session->set('cart', $this->cart);
        }
    }

    public function add(): void
    {
        try
        {
            $item = json_decode(file_get_contents('php://input'), true);
            $this->cart = $this->session->get('cart');
            $this->cart[] = $item;
            $this->session->set('cart', $this->cart);
            Response::json($this->cart);
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    public function remove($id)
    {
        $this->cart = $this->session->get('cart');
        unset($this->cart[$id]);
        $this->session->set('cart', $this->cart);
    }

    public function get($id)
    {
        $this->cart = $this->session->get('cart');
        return $this->cart[$id];
    }

    public function all()
    {
        $this->cart = $this->session->get('cart');
        return $this->cart;
    }

    public function clear()
    {
        $this->cart = [];
        $this->session->set('cart', $this->cart);
    }

    public function dump()
    {
        $this->cart = $this->session->get('cart');
        var_dump($this->cart);
    }

    public function __destruct()
    {
        $this->session->set('cart', $this->cart);
    }
}
