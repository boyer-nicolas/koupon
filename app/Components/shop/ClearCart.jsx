'use client';
import React from 'react';
import Cart from "../../Model/Cart";

export default class ClearCart extends React.Component
{
    constructor(props)
    {
        super(props);
        this.cart = new Cart();
    }

    clearCart()
    {
        this.cart.clear();
    }

    render()
    {
        return (
            <button onClick={() => this.clearCart()} className="btn btn-warning text-white">
                Clear Cart
            </button>
        );
    }
}