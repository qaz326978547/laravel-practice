<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use App\Models\Product\Category;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function getAll(): Collection
    {
        return Category::all();
    }

    public function getById(int $id): ?array
    {
        $category = Category::find($id);

        if ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'is_enabled' => $category->is_enabled,
            ];
        }
        return null;
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): Category
    {
        $category = Category::find($id);

        if ($category) {
            $category->update($data);
        }

        return $category;
    }

    public function delete(int $id): ?Category
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
        }

        return $category;
    }
}