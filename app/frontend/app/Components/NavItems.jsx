import Link from "next/link";
import Theme from "./Theme";
export default function NavItems()
{
    return (
        <>
            <li>
                <input type="text" placeholder="Search products" className="input input-bordered w-full max-w-xs" />

            </li>
            <li>
                <Link href="/">
                    <label htmlFor="app-drawer">
                        Home
                    </label>
                </Link>
            </li>
            <li>
                <Link href="/shop">
                    <label htmlFor="app-drawer">
                        Shop
                    </label>
                </Link>
            </li>
            <li className="mt-auto">
                <Theme />
            </li>

        </>
    );
}