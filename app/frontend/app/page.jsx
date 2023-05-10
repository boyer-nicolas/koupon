import React from "react"
import Link from "next/link"

export default function Index()
{
  return (
    <div className="hero min-h-screen">
      <div className="hero-content text-center">
        <div className="max-w-md">
          <h1 className="text-5xl font-bold">Hello there</h1>
          <p className="py-6">Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem quasi. In deleniti eaque aut repudiandae et a id nisi.</p>
          <Link href="/shop" className="btn btn-primary">Browse Shop</Link>
        </div>
      </div>
    </div>
  )
}