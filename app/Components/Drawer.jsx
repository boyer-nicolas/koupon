import NavItems from './NavItems';
import ExtraNav from './ExtraNav';
import Theme from './Theme';
export default function Drawer({ children })
{
    return (
        <div className="drawer">
            <input id="app-drawer" type="checkbox" className="drawer-toggle" />
            <div className="drawer-content flex flex-col">
                {children}
            </div>
            <div className="drawer-side">
                <label htmlFor="app-drawer" className="drawer-overlay"></label>
                <ul className="menu p-4 w-80 bg-base-100">
                    <ExtraNav />
                    <NavItems />
                    <li className="mt-auto">
                        <Theme />
                    </li>
                </ul>
            </div>
        </div>
    );
}