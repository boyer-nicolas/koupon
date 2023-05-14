'use client';
import React from 'react';
import Cart from "../../Model/Cart";
import { BiCartAdd, BiErrorCircle, BiCart } from "react-icons/bi";
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

        const cartPromise = async () =>
        {
            const response = await this.cart.add(this.product);
            if (response.status !== 200 || response.data.error)
            {
                response.data.error && console.error(response.data.error);
                this.setState({ btnContents: "Error", btnClass: "btn-error" });
                setTimeout(() => this.setState({
                    btnContents: "Add to Cart",
                    btnClass: "btn-primary",
                    btnIcon: <BiErrorCircle size={24} />
                }), 2000);
                throw new Error(response.data.error);
            }

            console.table(response.data.cart);


            this.setState({
                modalIsOpen: false,
                cartContents: response.data.cart,
                cartTotal: this.cart.getTotal(),
                btnContents: "In Cart",
                btnClass: "btn-secondary",
                btnIcon: <BiCart size={24} />,
            });
        }

        toast.promise(cartPromise(), {
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
                            {this.state.cartContents && this.state.cartContents.length > 0 && this.state.cartContents.map((item) =>
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