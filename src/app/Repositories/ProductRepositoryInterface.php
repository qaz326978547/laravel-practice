<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use App\Models\Product\Product;

interface ProductRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(int $id) : ?array;
    public function create(array $data) : Product;
    public function update(int $id, array $data) : ?array;
    public function delete(int $id) : ?Product;
    public function getOnSale() : Collection;
    public function updateOnSale(int $id, array $data) : ?array;
}