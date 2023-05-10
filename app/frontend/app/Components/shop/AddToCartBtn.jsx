'use client';
import React from 'react';
import Cart from "../../Model/Cart";
import { BiCartAdd, BiCheck, BiErrorCircle } from "react-icons/bi";

export default class AddToCartBtn extends React.Component
{
    constructor(props)
    {
        super(props);
        const requiredProperties = ["product"];
        requiredProperties.forEach((property) =>
        {
            if (!props.hasOwnProperty(property))
            {
                throw new Error(`AddToCartBtn is missing required property: ${property}`);
            }
        });

        this.product = props.product;
        this.cart = new Cart();

        this.state = {
            btnContents: "Add to Cart",
            btnIcon: <BiCartAdd size={24} />,
            btnClass: "btn-primary"
        };
    }

    addToCart()
    {
        this.setState({ btnContents: "Adding", btnClass: "btn-disabled loading", btnIcon: null });
        setTimeout(() =>
        {
            this.cart.add({ item: this.product }).then(() =>
            {
                this.setState({ btnContents: "Added!", btnClass: "btn-success", btnIcon: <BiCheck size={24} /> });
                setTimeout(() => this.setState({ btnContents: "Add to Cart", btnClass: "btn-primary", btnIcon: <BiCartAdd size={24} /> }), 2000);
            }
            ).catch((error) =>
            {
                console.error(error);
                this.setState({ btnContents: "Error", btnClass: "btn-error" });
                setTimeout(() => this.setState({ btnContents: "Add to Cart", btnClass: "btn-primary", btnIcon: <BiErrorCircle size={24} /> }), 2000);
            }
            );
        }, 500);
    }

    render()
    {
        return (
            <button onClick={() => this.addToCart()} className={"btn " + this.state.btnClass}>
                <span className='mr-1'>
                    {this.state.btnContents}
                </span>
                {this.state.btnIcon}
            </button>
        );
    }
}