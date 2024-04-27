<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Collection;

class JsonProductsRepository implements ProductsRepositoryInterface
{
    private const PRODUCTS_DATA_PATH = 'data/products.json';

    private $product_model;

    public function __construct()
    {
        $data = file_get_contents(storage_path(self::PRODUCTS_DATA_PATH));
        $this->product_model = json_decode($data, true)['data'];
    }

    public function findAll(): Collection
    {
        return collect($this->product_model);
    }
}
