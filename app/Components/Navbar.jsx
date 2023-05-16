import Link from 'next/link'
import NavItems from './NavItems';
import ExtraNav from './ExtraNav';
import Image from 'next/image';
import ProfilePic from "../assets/images/profile.png"
import Cart from "../Model/Cart"
import Theme from './Theme';

export default function Navbar()
{
    return (
        <>
            <div className="w-full bg-base-100 container mx-auto bg-base-100 z-50">
                <div className="navbar w-full text-center">
                    <p className='mx-auto font-bold'>
                        Limited time offer: Get 20% off on your first order with code <span className='text-primary ml-1'>FIRST20</span>
                    </p>
                </div>
                <div className="navbar">
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
                            <label tabIndex={0} className="btn btn-ghost btn-circle avatar">
                                <div className="w-10 rounded-full">
                                    <Image src={ProfilePic} alt="Profile Picture" />
                                </div>
                            </label>
                            <ul tabIndex={0} className="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
                                <li>
                                    <a className="justify-between">
                                        Orders
                                        <span className="badge badge-primary">New</span>
                                    </a>
                                </li>
                                <li><a>Details</a></li>
                                <li><a>Addresses</a></li>
                                <li><a>Invoices</a></li>
                                <li><a>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}