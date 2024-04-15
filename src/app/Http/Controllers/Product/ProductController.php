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
     * GET /products 取得所有產品
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        $products = Product::with('category')->get();
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
        $product = Product::with('category')->find($id);
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
        ];
        return response()->json([
            'data' => $product
        ], Response::HTTP_OK);
    }
    /**
     * POST /products 新增產品
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        Product::create($data);
        //找出Image裡面product_id是null的資料
        $images = Image::whereNull('product_id')->get();
        //將product_id是null的資料全部更新成最新的product_id
        $images->each(function ($image) { //each()對集合中的每個項目執行回調 如果使用map()就需要返回後再使用save()更新
            $image->update([
                'product_id' => Product::latest()->first()->id //latest()取得最新一筆資料 first()取得第一筆資料
            ]);
        });
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
        $product->delete();
        return response()->json([
            'message' => '刪除成功'
        ], Response::HTTP_OK);
    }
}
