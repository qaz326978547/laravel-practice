<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use App\Models\Product\Category;
use Symfony\Component\HttpFoundation\Response;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{

    private $category_model;
    public function __construct(Category $category_model)
    {
        $this->category_model = $category_model;
    }
    public function getAll(): Collection
    {
        return $this->category_model->all();
    }

    public function getById(int $id): ?array
    {
        $category = $this->category_model->find($id);
        if(!$category){
            throw new \Exception('Category not found',Response::HTTP_NOT_FOUND);
        }else{
            return [
                'id' => $category->id,
                'name' => $category->name,
                'is_enabled' => $category->is_enabled,
            ];
        }
    }

    public function create(array $data): Category
    {
        return $this->category_model->create($data);
    }

    public function update(int $id, array $data): Category
    {
        $category = $this->category_model->find($id);
        if(!$category){
            throw new \Exception('Category not found',Response::HTTP_NOT_FOUND);
        }else{
            $category->update($data);
        }
        return $category;
    }

    public function delete(int $id): ?Category
    {
        $category = $this->category_model->find($id);
        if(!$category){
            throw new \Exception('Category not found',Response::HTTP_NOT_FOUND);
        }else{
            return $category;
        }
    }
}