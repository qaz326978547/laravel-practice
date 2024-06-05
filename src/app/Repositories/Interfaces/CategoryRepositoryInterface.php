<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;
use App\Models\Product\Category;

interface CategoryRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(int $id): ?array;
    public function create(array $data): Category;
    public function update(int $id, array $data): Category;
    public function delete(int $id): ?Category;
}
