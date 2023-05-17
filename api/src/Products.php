<?php

namespace Koupon\Api;

use \Exception;
use Koupon\Api\Log;
use Koupon\Api\Product;

final class Products
{
    private $data;
    private $products;
    public function __construct()
    {
        $this->data = "../../data/products.json";
    }

    public function get(): array
    {
        $productsJson = json_decode(file_get_contents($this->data), true);
        $products = [];
        foreach ($productsJson as $product)
        {
            $products[] = new Product($product['id'], $product['title'], $product['description'], $product['image'], $product['link'], $product['tags'], $product['price'], $product['currency'], $product['new'], $product['quantity']);
        }

        $this->setProducts($products);

        return $this->getProducts();
    }

    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getProductById(int $id): array
    {

        foreach ($this->products as $product)
        {
            if ($product['id'] == $id)
            {
                return $product;
            }
        }
        return [];
    }
}
