<?php

namespace App\Repositories;

use App\Models\Product\Product;
use Illuminate\Support\Collection;

class EloquentProductRepository implements ProductRepositoryInterface
{

    public function getAll(): Collection
    {
        $products = Product::with(['category', 'images'])->get();

        return $products->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'category' => $product->category->name,
                'description' => $product->description,
                'content' => $product->content,
                'origin_price' => $product->origin_price,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'is_enabled' => $product->is_enabled,
                'unit' => $product->unit,
                'image' => $product->images->where('type', 'main')->first()->imageUrl ?? null,
                'images' => $product->images->where('type', 'sub')->pluck('imageUrl') ?? [],
                'on_sale_start' => $product->on_sale_start,
                'on_sale_end' => $product->on_sale_end,
                'is_on_sale' => $product->is_on_sale,
            ];
        });
    }

    public function getById(int $id) : ?array
    {
        $product = Product::with(['category', 'images'])->find($id);

        if ($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'category' => $product->category->name,
                'description' => $product->description,
                'content' => $product->content,
                'origin_price' => $product->origin_price,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'is_enabled' => $product->is_enabled,
                'unit' => $product->unit,
                'image' => $product->images->where('type', 'main')->first()->imageUrl ?? null,
                'images' => $product->images->where('type', 'sub')->pluck('imageUrl') ?? [],
                'on_sale_start' => $product->on_sale_start,
                'on_sale_end' => $product->on_sale_end,
                'is_on_sale' => $product->is_on_sale,
            ];
        }

        return null;
    }

    public function create(array $data) : Product
    {
        $product = Product::create($data);
        $product->images()->attach($data['images']);
        return $product;
    }

    public function update(int $id, array $data) : Product
    {
        $product = Product::find($id);
        if(!$product){
            throw new \Exception('Product not found');
        }

        $product->update($data);
        return $product;
    }

    public function delete(int $id) : ?Product
    {
        $product = Product::find($id);
        if ($product) {
            $product->images()->detach();
            $product->delete();
        }
        return $product;
    }
}