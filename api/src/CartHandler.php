<?php

namespace Koupon\Api;

use Koupon\Api\Cart;

final class CartHandler
{
    private Cart $cart;
    public function __construct()
    {
        $this->cart = new Cart('1', 0, []);
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function addItem(CartItem $item): void
    {
        $this->cart->addItem($item);
    }

    public function removeItem(string $itemId): void
    {
        $this->cart->removeItem($itemId);
    }

    public function applyCoupon(Coupon $coupon): void
    {
        $this->cart->applyCoupon($coupon);
    }

    public function removeCoupon(Coupon $coupon): void
    {
        $this->cart->removeCoupon($coupon);
    }

    public function getCartTotal(): float
    {
        return $this->cart->getTotal();
    }
}
