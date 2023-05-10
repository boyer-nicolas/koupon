'use client';

import Image from "next/image";
import React from 'react';

export default class Cart extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {

        };
    }

    render()
    {
        return (
            <div className="hero min-h-screen bg-base-200">
                <div className="hero-content text-center">
                    <div className="max-w-md">
                        <h1 className="text-5xl font-bold">Cart</h1>
                        <p className="py-6">This is your cart.</p>
                        <div className="grid grid-cols-3 gap-4">
                            {this.state.cart && this.state.cart.map((item, index) =>
                            {
                                return (
                                    <div className="card shadow-lg compact side bg-base-100" key={index}>
                                        <figure>
                                            <Image alt={item.name} src={item.image} />
                                        </figure>
                                        <div className="justify-end card-body">
                                            <h2 className="card-title">{item.name}</h2>
                                            <p>{item.price}</p>
                                        </div>
                                    </div>
                                )
                            })}
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}