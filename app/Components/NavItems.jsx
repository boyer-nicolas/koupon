import Link from "next/link";

export default function NavItems()
{
    return (
        <>
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
        </>
    );
}