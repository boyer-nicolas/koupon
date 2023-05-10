import Image from "next/image";
import Link from "next/link";
import AddToCartBtn from "./AddToCartBtn"
import classname from "classnames";

export default function ProductCard({ product, ...props })
{
    const requiredProductProperties = ["id", "title", "description", "image", "link", "tags", "price", "currency", "new"];

    requiredProductProperties.forEach((property) =>
    {
        if (!product.hasOwnProperty(property))
        {
            throw new Error(`Product is missing required property: '${property}' for product: ${product.id}`);
        }
    });

    return (
        <div className={classname("card shadow-xl", props.className)} style={props.style}>
            <figure>
                <Image width={400} height={200} src={product.image} alt="Shoes" className="w-full" />
            </figure>
            <div className="card-body">
                <div className="card-actions justify-start">
                    {product.tags.map((tag, index) => (
                        <div key={index} className="badge badge-outline">{tag}</div>
                    ))}
                </div>
                <h2 className="card-title">
                    <Link href={product.link}>
                        {product.title}
                    </Link>
                    {product.isNew && <div className="badge badge-secondary">NEW</div>}
                </h2>
                <p>
                    {product.description}
                </p>
                <div className="card-actions w-full mt-5 justify-between items-center">
                    <div className="card-actions">
                        <span className="text-2xl font-bold">
                            {product.currency === "USD" && "$"}
                            {product.price}
                            {product.currency === "EUR" && "€"}
                        </span>
                    </div>
                    <div className="card-actions flex justify-end flex-nowrap">
                        <Link href={product.link} className="btn btn-shadow">View Details</Link>
                        <AddToCartBtn product={product} />
                    </div>
                </div>
            </div>
        </div>
    );
}