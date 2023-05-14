import ProductCard from "../Components/shop/ProductCard";

export default function Shop()
{
    const products = {
        "shoes": [
            {
                "id": 1,
                "title": "Shoes",
                "description": "If a dog chews shoes whose shoes does he choose?",
                "image": "/images/products/shoe.jpg",
                "link": "/shop/shoes/1",
                "tags": ["Fashion", "Products"],
                "price": "99.99",
                "currency": "EUR",
                "new": true,
                "quantity": 1
            },
            {
                "id": 2,
                "title": "Shoes",
                "description": "If a dog chews shoes whose shoes does he choose?",
                "image": "/images/products/shoe.jpg",
                "link": "/shop/shoes/2",
                "tags": ["Fashion", "Products"],
                "price": "99.99",
                "currency": "EUR",
                "new": true,
                "quantity": 1

            },
            {
                "id": 3,
                "title": "Shoes",
                "description": "If a dog chews shoes whose shoes does he choose?",
                "image": "/images/products/shoe.jpg",
                "link": "/shop/shoes/2",
                "tags": ["Fashion", "Products"],
                "price": "99.99",
                "currency": "EUR",
                "new": true,
                "quantity": 1

            },
            {
                "id": 4,
                "title": "Shoes",
                "description": "If a dog chews shoes whose shoes does he choose?",
                "image": "/images/products/shoe.jpg",
                "link": "/shop/shoes/2",
                "tags": ["Fashion", "Products"],
                "price": "99.99",
                "currency": "EUR",
                "new": true,
                "quantity": 1

            },
            {
                "id": 5,
                "title": "Shoes",
                "description": "If a dog chews shoes whose shoes does he choose?",
                "image": "/images/products/shoe.jpg",
                "link": "/shop/shoes/2",
                "tags": ["Fashion", "Products"],
                "price": "99.99",
                "currency": "EUR",
                "new": true,
                "quantity": 1

            },
            {
                "id": 6,
                "title": "Shoes",
                "description": "If a dog chews shoes whose shoes does he choose?",
                "image": "/images/products/shoe.jpg",
                "link": "/shop/shoes/2",
                "tags": ["Fashion", "Products"],
                "price": "99.99",
                "currency": "EUR",
                "new": true,
                "quantity": 1

            },
            {
                "id": 7,
                "title": "Shoes",
                "description": "If a dog chews shoes whose shoes does he choose?",
                "image": "/images/products/shoe.jpg",
                "link": "/shop/shoes/2",
                "tags": ["Fashion", "Products"],
                "price": "99.99",
                "currency": "EUR",
                "new": true,
                "quantity": 1

            },
            {
                "id": 8,
                "title": "Shoes",
                "description": "If a dog chews shoes whose shoes does he choose?",
                "image": "/images/products/shoe.jpg",
                "link": "/shop/shoes/2",
                "tags": ["Fashion", "Products"],
                "price": "99.99",
                "currency": "EUR",
                "new": true,
                "quantity": 1

            },
            {
                "id": 9,
                "title": "Shoes",
                "description": "If a dog chews shoes whose shoes does he choose?",
                "image": "/images/products/shoe.jpg",
                "link": "/shop/shoes/2",
                "tags": ["Fashion", "Products"],
                "price": "99.99",
                "currency": "EUR",
                "new": true,
                "quantity": 1

            },
        ]
    }

    return (
        <div className="flex container mx-auto py-20 px-5">
            <div className="w-[30%] lg:mr-10 hidden xl:block">
                <div className="card bg-base-100 shadow-xl p-5">
                    <div className="flex items-center justify-between mb-5">
                        <h2 className="font-bold mr-5">Sidebar w/filters</h2>
                        <button className="btn btn-primary btn-sm">Clear</button>
                    </div>
                    <form>
                        <div className="brands mb-5">
                            <h2 className="font-bold mb-2">Brands</h2>
                            <div className="form-control">
                                <label className="label cursor-pointer">
                                    <span className="label-text font-semibold">Adidas</span>
                                    <input type="checkbox" className="checkbox checkbox-primary" />
                                </label>
                            </div>
                            <div className="form-control">
                                <label className="label cursor-pointer">
                                    <span className="label-text font-semibold">Converse</span>
                                    <input type="checkbox" className="checkbox checkbox-primary" />
                                </label>
                            </div>
                            <div className="form-control">
                                <label className="label cursor-pointer">
                                    <span className="label-text font-semibold">Vans</span>
                                    <input type="checkbox" className="checkbox checkbox-primary" />
                                </label>
                            </div>
                            <div className="form-control">
                                <label className="label cursor-pointer">
                                    <span className="label-text font-semibold">Element</span>
                                    <input type="checkbox" className="checkbox checkbox-primary" />
                                </label>
                            </div>
                            <div className="form-control">
                                <label className="label cursor-pointer">
                                    <span className="label-text font-semibold">Dock Martins</span>
                                    <input type="checkbox" className="checkbox checkbox-primary" />
                                </label>
                            </div>
                        </div>
                        <div className="pricerange mb§5">
                            <h2 className="font-bold mb-2">Price Range</h2>
                            <input type="range" min="0" max="100" className="range" step="25" />
                            <div className="w-full flex justify-between text-xs px-2">
                                <span>0/20</span>
                                <span>20/50</span>
                                <span>50/100</span>
                                <span>100/200</span>
                                <span>200+</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div className="mx-auto">
                <div className="flex items-center justify-between mb-5">
                    <h2 className="font-bold text-xl">All products</h2>
                    {/* For quick development purposes */}
                    {/* <ClearCart /> */}
                    <form>
                        <select className="select w-full max-w-xs outline outline-2 outline-gray-200">
                            <option disabled defaultValue>Sort by</option>
                            <option>Featured Items</option>
                            <option>Price - Low to High</option>
                            <option>Price - High to Low</option>
                            <option>Best Sellers</option>
                            <option>New Arrivals</option>
                        </select>
                    </form>
                </div>
                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

                    {products.shoes.map((product, index) =>
                    {
                        return (
                            <ProductCard key={index} product={product} className={"animate__animated animate__fadeIn animate__fast"} style={{ animationDelay: `${index * 0.1}s` }} />
                        )
                    })}
                </div >
                <div className="flex items-center justify-center">
                    <div className="btn-group mt-5 mx-auto">
                        <button className="btn btn-ghost" disabled>Previous</button>
                        <button className="btn btn-ghost btn-active">1</button>
                        <button className="btn btn-ghost">2</button>
                        <button className="btn btn-ghost">3</button>
                        <button className="btn btn-ghost">4</button>
                        <button className="btn btn-ghost">Next</button>
                    </div>
                </div>
            </div>
        </div>
    );
}