'use client';
import React from 'react';
import Cart from "../../Model/Cart";
import { BiCartAdd, BiCheck, BiErrorCircle, BiCart } from "react-icons/bi";
import toast from 'react-hot-toast';
import Modal from 'react-modal';

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
            btnClass: "btn-primary",
            cartContents: [],
            cartTotal: 0,
        };

    }

    componentDidMount()
    {
        Modal.setAppElement('#koupon');
    }

    addToCart()
    {
        this.setState({
            btnContents: "Adding",
            btnClass: "btn-disabled loading",
            btnIcon: null
        });

        const addToCart = () => this.cart.add({ item: this.product }).then(() =>
        {
            const cartContents = this.cart.getContents();

            this.setState({
                modalIsOpen: true,
                cartContents: cartContents.map((item) =>
                {
                    return {
                        id: item.item.id,
                        contents: `${item.quantity}x ${item.item.name} @ ${item.item.price}`
                    };
                }),
                cartTotal: this.cart.getTotal()
            });

            setTimeout(() =>
            {
                this.setState({
                    btnContents: "In Cart",
                    btnClass: "btn-secondary",
                    btnIcon: <BiCart size={24} />,
                });
            }, 2000);
        }
        ).catch((error) =>
        {
            console.error(error);
            this.setState({ btnContents: "Error", btnClass: "btn-error" });
            setTimeout(() => this.setState({
                btnContents: "Add to Cart",
                btnClass: "btn-primary",
                btnIcon: <BiErrorCircle size={24} />
            }), 2000);
        });

        toast.promise(addToCart(), {
            loading: 'Adding to cart...',
            success: <b>Added to cart!</b>,
            error: <b>Could not add to cart.</b>,
        });
    }

    render()
    {
        return (
            <>
                <button onClick={() => this.addToCart()} className={"btn " + this.state.btnClass}>
                    <span className='mr-1'>
                        {this.state.btnContents}
                    </span>
                    {this.state.btnIcon}
                </button>
                <div id="koupon"></div>
                <Modal isOpen={this.state.modalIsOpen} onRequestClose={() => this.setState({ modalIsOpen: false })} contentLabel="Example Modal" className="modal cursor-pointer modal-open">
                    <label className="modal-box relative animate__animated animate__fadeInUp animate__fast">
                        <h3 className="text-lg font-bold">Cart Results</h3>
                        <ul>
                            {this.state.cartContents.map((item) =>
                            {
                                <li key={item.id}>
                                    {item.contents}
                                </li>
                            })}
                        </ul>
                        <p>Total: ${this.state.cartTotal}</p>
                        <button className='btn btn-primary mt-5' onClick={() => this.setState({ modalIsOpen: false })}>close</button>
                    </label>

                </Modal>
            </>
        );
    }
}