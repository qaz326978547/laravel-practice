<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;
use App\Models\Product\Product;

interface ProductRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(int $id): ?array;
    public function create(array $data): Product;
    public function update(int $id, array $data): Product;
    public function delete(int $id): ?Product;
}
