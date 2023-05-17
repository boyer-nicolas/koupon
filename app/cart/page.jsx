'use client';

import Image from "next/image";
import Link from "next/link";
import React from 'react';
import Cart from "../Model/Cart";
import toast from 'react-hot-toast';
import CartLoader from "../Components/cart/CartLoader";

export default class CartPage extends React.Component
{
    constructor(props)
    {
        super(props);
        this.cart = new Cart();
        this.state = {
            cart: [],
            loading: true,
            coupon: '',
            items: []
        };
    }

    componentDidMount()
    {
        this.updateItems();
    }

    updateItems()
    {
        this.setState({ loading: true });
        this.cart.getContents().then((contents) =>
        {
            const cartItems = contents.data.cart.items;
            const items = Object.entries(cartItems).map(([key, value]) =>
            {
                return value;
            });

            console.log(contents.data.cart);

            this.setState({
                cart: contents.data.cart,
                loading: false,
                items: items
            });
        });
    }

    applyCoupon(e)
    {
        e.preventDefault();

        const couponPromise = async () =>
        {
            return this.cart.applyCoupon(this.state.coupon).then((data) =>
            {
                if (data.data.error)
                {
                    const form = document.getElementById('coupon-form');
                    const message = form.querySelector('.form-validation-message');
                    message.innerHTML = data.data.error;
                    throw new Error(data.data.error);
                }
                else
                {
                    const form = document.getElementById('coupon-form');
                    const message = form.querySelector('.form-validation-message');
                    message.innerHTML = '';
                }

                return this.updateItems();
                // this.setState({ cart: contents.data.cart, loading: false });
            });
        }

        toast.promise(couponPromise(), {
            loading: 'Applying coupon...',
            success: <b>Coupon applied!</b>,
            error: <b>Could not apply coupon.</b>,
        });


    }

    removeItem(item)
    {
        const removePromise = async () =>
        {
            return this.cart.removeItem(item).then((result) =>
            {
                return this.updateItems();
            });
        }

        toast.promise(removePromise(), {
            loading: 'Removing item...',
            success: <b>Item removed!</b>,
            error: <b>Could not remove item.</b>,
        });
    }

    removeDiscount()
    {
        const removePromise = async () =>
        {
            return this.cart.removeDiscount().then((result) =>
            {
                return this.updateItems();
            });
        }

        toast.promise(removePromise(), {
            loading: 'Removing discount...',
            success: <b>Discount removed!</b>,
            error: <b>Could not remove discount.</b>,
        });
    }

    render()
    {
        return (
            <div className="bg-base-100 container mx-auto my-20">
                <div className="text-start">
                    <div className="">
                        <section className="mb-3">
                            <h1 className="text-5xl font-bold">Cart</h1>
                        </section>
                        <section className="mb-3">
                            {this.state.cart && this.state.cart.hasCoupon && (
                                <div className="my-10 p-10 shadow-lg rounded-lg bg-primary text-white flex justify-around">

                                    <div className="font-bold">
                                        <span className="text-sm text-white-500">Coupon: </span>
                                        <span className="badge badge-success">
                                            {this.state.cart.coupon && this.state.cart.coupon.code}
                                        </span>
                                    </div>
                                    <div>
                                        <span className="text-sm text-white-500">Discount amount: </span>
                                        <span className="badge badge-success">
                                            -{this.state.cart && this.state.cart.coupon && this.state.cart.coupon.discountAmount}%
                                        </span>
                                    </div>
                                    <div>
                                        <button className="btn btn-xs" onClick={() => this.removeDiscount()}>remove</button>
                                    </div>

                                </div>
                            )}
                            <div className="overflow-x-auto w-full shadow-lg rounded-lg">
                                {this.state.items.length > 0 && (
                                    <>
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
                                                    <th>Total Price</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {this.state.cart && this.state.items && this.state.items.length > 0 && this.state.items.map((item, index) =>
                                                {
                                                    return (
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
                                                                            <span className="flex">
                                                                                {item.tags.map((tag, index) =>
                                                                                {
                                                                                    return (
                                                                                        <span key={index} className="badge badge-outline badge-accent mr-1">{tag}</span>
                                                                                    )
                                                                                })}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                {item.quantity}
                                                            </td>
                                                            <td>
                                                                {item.price}€
                                                            </td>
                                                            <td>
                                                                {Math.round(item.price * item.quantity * 100) / 100}€
                                                            </td>
                                                            <th>
                                                                <Link href={item.link} className="btn btn-primary btn-xs">details</Link>
                                                                <button className="btn btn-error btn-xs" onClick={() => this.removeItem(item)}>remove</button>
                                                            </th>
                                                        </tr>
                                                    )
                                                })}
                                                {/* Total */}
                                                <tr>
                                                    <th></th>
                                                    <th>
                                                        <div className="font-bold flex flex-col">
                                                            <span>Total</span>
                                                            <span className="text-sm text-gray-500">Tax included.</span>
                                                        </div>
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>
                                                        {this.state.cart && this.state.cart.hasCoupon && (
                                                            <span className="line-through mr-1 text-gray-400">
                                                                {this.state.cart && this.state.cart.total && this.state.cart.initialTotal}€
                                                            </span>
                                                        )}
                                                        <span>
                                                            {this.state.cart && this.state.cart.total && this.state.cart.total}€
                                                        </span>
                                                        {this.state.cart && this.state.cart.hasCoupon && (
                                                            <span className="badge badge-success ml-1">
                                                                -{this.state.cart && this.state.cart.recuctionAmount && this.state.cart.recuctionAmount}€
                                                            </span>
                                                        )}
                                                    </th>
                                                    <th></th>
                                                </tr>
                                            </tbody >
                                        </table >
                                        <div className="flex justify-between p-10">
                                            <form className="max-w-xs flex" onSubmit={(e) => this.applyCoupon(e)} id="coupon-form">
                                                <div>
                                                    <input type="text" placeholder="Enter coupon code" className="input input-bordered w-full max-w-xs" value={this.state.coupon} onChange={(e) => this.setState({ coupon: e.target.value })} />
                                                    <p className="text-xs text-red-500 form-validation-message mt-3"></p>
                                                </div>
                                                <button className="btn btn-primary">Apply</button>
                                            </form>
                                            <button href="/checkout" className="btn btn-primary">Checkout</button>
                                        </div>
                                    </>
                                )}
                                {this.state.loading && (
                                    <CartLoader />
                                )}
                                {(!this.state.cart || this.state.items.length === 0) &&
                                    <>
                                        {!this.state.loading && (
                                            <div className="w-full mx-auto p-10">
                                                <h2 className="card-title">Your cart is empty</h2>
                                                <Link href="/shop" className="btn btn-primary">
                                                    Browse our products
                                                </Link>
                                            </div>
                                        )}
                                    </>
                                }
                            </div >

                        </section>
                    </div>
                </div>
            </div >
        );
    }
}