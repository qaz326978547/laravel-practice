<?php

namespace App\Http\Controllers\Product;

// use Illuminate\Http\Response;

use App\Models\Product\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response; //使用於狀態碼
use App\Http\Requests\ProductRequest;
use App\Http\Controllers\Controller;
use App\Models\Product\Image;

class ProductController extends Controller
{
    /**
     * GET /products 取得所有產品並透過with()方法取得關聯資料
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        $products = Product::with(['category', 'images'])->get();
        $products = $products->map(function ($product) {
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
                //pluck()方法取得集合中所有指定的key
                'on_sale_start' => $product->on_sale_start,
                'on_sale_end' => $product->on_sale_end,
            ];
        });
        return response()->json([
            'data' => $products
        ], Response::HTTP_OK);
    }
    /**
     * GET /products/{id} 取得單一產品
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $product = Product::with(['category', 'images'])->find($id);
        if (!$product) {
            return response()->json([
                'message' => '找不到產品'
            ], Response::HTTP_NOT_FOUND);
        }
        $product = [
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
            'image' => $product->images()->where('type', 'main')->first()->imageUrl,
            'images' => $product->images()->where('type', 'sub')->pluck('imageUrl'),
            'on_sale_start' => $product->on_sale_start,
            'on_sale_end' => $product->on_sale_end,
        ];
        return response()->json([
            'data' => $product
        ], Response::HTTP_OK);
    }
    /**
     * POST /products 新增產品 並新增product_images中介表資料
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        $product = Product::create($data);
        $product->images()->attach($data['images']); //attach()方法新增多對多關聯 並在中介表products_images中新增資料
        return response()->json([
            'data' => $data,
            'message' => '新增成功'
        ], Response::HTTP_CREATED);
    }


    /**
     * PUT /products/{id} 更新產品
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id): JsonResponse
    {
        $data = $request->validated();
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => '找不到產品'
            ], Response::HTTP_NOT_FOUND);
        }
        $product->update($data);

        return response()->json([
            'data' => $data,
            'message' => '更新成功'
        ], Response::HTTP_OK);
    }


    /**
     * DELETE /products/{id} 刪除產品
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => '找不到產品'
            ], Response::HTTP_NOT_FOUND);
        }
        $product->images()->detach(); //detach()方法刪除中介表products_images中的資料
        $product->delete();
        return response()->json([
            'message' => '刪除成功'
        ], Response::HTTP_OK);
    }
}
