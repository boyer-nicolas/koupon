'use client';

import Image from "next/image";
import Link from "next/link";
import React from 'react';
import Cart from "../Model/Cart";

export default class CartPage extends React.Component
{
    constructor(props)
    {
        super(props);
        this.cart = new Cart();
        this.state = {
            cart: [],
            loading: true
        };

        this.cart.getContents().then((contents) =>
        {
            this.setState({ cart: contents.data.cart, loading: false });
        });
    }

    applyCoupon(e)
    {
        e.preventDefault();
        let coupon = e.target.value;
        console.log(coupon);

        this.cart.applyCoupon(coupon).then((contents) =>
        {
            this.setState({ cart: contents.data.cart, loading: false });
        });
    }

    render()
    {
        return (
            <div className="hero min-h-screen bg-base-100">
                <div className="hero-content text-center">
                    <div className="">
                        <section className="mb-3">
                            <h1 className="text-5xl font-bold">Cart</h1>
                        </section>
                        <section className="mb-3">
                            <form className="max-w-sm mx-auto flex">
                                <input type="text" placeholder="Do you have a coupon code ?" className="input input-bordered w-full max-w-xs" />
                                <button className="btn btn-primary" onClick={(e) => this.applyCoupon(e)}>Apply</button>
                            </form>
                        </section>
                        <section className="mb-3">
                            <div className="overflow-x-auto w-full">
                                {this.state.cart && this.state.cart.items && this.state.cart.items.length > 0 && (
                                    <table className="table w-full">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <label>
                                                        <input type="checkbox" className="checkbox" />
                                                    </label>
                                                </th>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {this.state.cart && this.state.cart.items && this.state.cart.items.length > 0 && this.state.cart.items.map((item, index) =>
                                            {
                                                return (
                                                    <>
                                                        <tr key={index}>
                                                            <th>
                                                                <label>
                                                                    <input type="checkbox" className="checkbox" />
                                                                </label>
                                                            </th>
                                                            <td>
                                                                <div className="flex items-center space-x-3">
                                                                    <div className="avatar">
                                                                        <div className="mask mask-squircle w-12 h-12">
                                                                            <Image alt={item.name} src={item.image} width={48} height={48} />
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <div className="font-bold flex flex-col">
                                                                            {item.name}
                                                                            {item.tags.map((tag, index) =>
                                                                            {
                                                                                return (
                                                                                    <span key={index} className="badge badge-outline badge-accent">{tag}</span>
                                                                                )
                                                                            })}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                {item.quantity}
                                                            </td>
                                                            <td>
                                                                {item.price}
                                                            </td>
                                                            <th>
                                                                <Link href={item.link} className="btn btn-primary btn-xs">details</Link>
                                                                <button className="btn btn-error btn-xs">remove</button>
                                                            </th>
                                                        </tr>
                                                    </>
                                                )
                                            })}
                                        </tbody >
                                    </table >
                                )}
                            </div >
                            {(!this.state.cart || this.state.cart.length === 0) &&
                                <>
                                    {!this.state.loading && (
                                        <div className="shadow-lg w-full bg-base-100 p-10 mx-auto rounded-lg">
                                            <h2 className="card-title">Your cart is empty</h2>
                                            <Link href="/shop" className="btn btn-primary">
                                                Browse our products
                                            </Link>
                                        </div>
                                    )}
                                </>
                            }

                            {this.state.loading && (
                                <div className="shadow-lg w-full bg-base-100 p-10 mx-auto rounded-lg">
                                    <h2 className="card-title">Loading...</h2>
                                </div>

                            )}
                        </section>
                    </div>
                </div>
            </div >
        );
    }
}