<?php

namespace App\Repositories\Eloquent;

use App\Models\Product\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class EloquentProductRepository implements ProductRepositoryInterface
{
    private $product_model;
    public function __construct(Product $product_model)
    {
        $this->product_model = $product_model;
    }
    public function formatProduct($product): array
    {
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
    public function getAll(): Collection //可使用php artisan tinker 測試 Cache::get('products')
    {
        return Cache::remember('products', 60, function () {
            $products = $this->product_model->with(['category', 'images'])->get();
            return $products->map(function ($product) {
                return $this->formatProduct($product);
            });
        });
    }

    public function getById(int $id): ?array
    {
        $product = $this->product_model->with(['category', 'images'])->find($id);
        if (!$product) {
            throw new \Exception('Product not found', Response::HTTP_NOT_FOUND);
        }
        return $this->formatProduct($product);
    }

    public function create(array $data): Product
    {
        $product = $this->product_model->create($data);
        $product->images()->attach($data['images']); //attach() 方法會將圖片 id 加入 pivot table
        return $product;
    }

    public function update(int $id, array $data): array
    {
        $product = $this->product_model->find($id);
        if (!$product) {
            throw new \Exception('Product not found', Response::HTTP_NOT_FOUND);
        }

        $product->update($data);
        if (isset($data['images'])) {
            $product->images()->sync($data['images']);  // 同步更新圖片
            //如果同時存在陣列中和當前商品的圖片中，則保留，否則刪除
            //若已存在陣列中的id但不存在於當前商品的圖片中，則新增
        }
        return $this->formatProduct($product->load('images'));
    }

    public function delete(int $id): ?Product
    {
        $product = $this->product_model->find($id);
        if (!$product) {
            throw new \Exception('Product not found', Response::HTTP_NOT_FOUND);
        } else {
            $product->images()->detach();
            $product->delete();
        }

        return $product;
    }

    public function getOnSale(): Collection
    {
        if (!method_exists($this->product_model, 'getOnSale')) {
            throw new \Exception('Method getOnSale does not exist in Product model', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $products = $this->product_model->getOnSale()->get();
        return $products->map(function ($product) {
            return $this->formatProduct($product);
        });
    }

    public function updateOnSale(int $id, array $data): array
    {
        $product = $this->product_model->find($id);
        if (!$product) {
            throw new \Exception('Product not found', Response::HTTP_NOT_FOUND);
        }
        $product->is_on_sale = $data['is_on_sale'];

        return $this->formatProduct($product);
    }
}
