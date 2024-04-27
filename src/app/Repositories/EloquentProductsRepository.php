<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product\Product;
use Illuminate\Support\Collection;

class EloquentProductsRepository implements ProductsRepositoryInterface
{
    private $product_model;

    public function __construct(Product $product_model)
    {
        $this->product_model = $product_model;
    }

    public function findAll(): Collection
    {
        return $this->product_model->all();
    }
}
