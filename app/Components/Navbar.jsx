import Link from 'next/link'
import NavItems from './NavItems';
import ExtraNav from './ExtraNav';
import Image from 'next/image';
import ProfilePic from "../assets/images/profile.png"
import Cart from "../Model/Cart"
import Theme from './Theme';

export default function Navbar()
{
    console.log("Navbar");
    const cart = new Cart();
    console.log(cart.count());

    return (
        <div className="w-full navbar bg-base-100 container mx-auto">
            <div className="flex-none lg:hidden">
                <label htmlFor="app-drawer" className="btn btn-square btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" className="inline-block w-6 h-6 stroke-current"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </label>
            </div>
            <div className="flex-1 lg:flex-none">
                <Link href="/" className="btn btn-ghost normal-case text-xl">Koupon</Link>
            </div>
            <div className="flex-1 hidden lg:block">
                <ul className="menu menu-horizontal px-1">
                    <NavItems />
                </ul>
            </div>
            <div className="flex-none">
                <ul className="menu menu-horizontal px-1 hidden lg:flex">
                    <ExtraNav />
                    <li className='hidden lg:block ml-5'>
                        <Theme />
                    </li>
                </ul>
                <div className="dropdown dropdown-end">
                    <label tabIndex={0} className="btn btn-ghost btn-circle">
                        <div className="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <span className="badge badge-sm indicator-item">
                                {cart.count()}
                            </span>
                        </div>
                    </label>
                    <div tabIndex={0} className="mt-3 card card-compact dropdown-content w-52 bg-base-100 shadow">
                        <div className="card-body">
                            <span className="font-bold text-lg">
                                {cart.count()}
                                Items
                            </span>
                            <span className="text-info">Subtotal: {cart.getTotal()}</span>
                            <div className="card-actions">
                                <Link href="/cart" className="btn btn-primary btn-block">View cart</Link>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="dropdown dropdown-end">
                    <label tabIndex={0} className="btn btn-ghost btn-circle avatar">
                        <div className="w-10 rounded-full">
                            <Image src={ProfilePic} alt="Profile Picture" />
                        </div>
                    </label>
                    <ul tabIndex={0} className="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
                        <li>
                            <a className="justify-between">
                                Profile
                                <span className="badge">New</span>
                            </a>
                        </li>
                        <li><a>Settings</a></li>
                        <li><a>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    );
}