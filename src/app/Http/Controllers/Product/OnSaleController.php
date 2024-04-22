<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateOnSaleRequest;

class OnSaleController extends Controller
{
    /**
     * GET /on-sale 取得所有當前特價商品
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $data = Product::with(['category', 'images'])
            ->where('is_on_sale', 1)
            ->get();
        $data = $data->map(function ($product) {
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
            ];
        });
        return response()->json($data, Response::HTTP_OK);
    }

    // /**
    //  * GET /on-sale 取得特定時間特價商品
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(UpdateOnSaleRequest $request): JsonResponse
    // {
    //     $data = $request->validated();
    //     //取得$data['on_sale_start'] 與 $data['on_sale_end'] 期間的特價商品
    //     $data = Product::with(['category', 'images'])
    //         ->where('on_sale_start', '<=', $data['on_sale_start'])
    //         ->where('on_sale_end', '>=', $data['on_sale_end'])
    //         ->get();
    //     $data = $data->map(function ($product) {
    //         return [
    //             'id' => $product->id,
    //             'title' => $product->title,
    //             'category' => $product->category->name,
    //             'description' => $product->description,
    //             'content' => $product->content,
    //             'origin_price' => $product->origin_price,
    //             'price' => $product->price,
    //             'quantity' => $product->quantity,
    //             'is_enabled' => $product->is_enabled,
    //             'unit' => $product->unit,
    //             'image' => $product->images->where('type', 'main')->first()->imageUrl ?? null,
    //             'images' => $product->images->where('type', 'sub')->pluck('imageUrl') ?? [],
    //             'on_sale_start' => $product->on_sale_start,
    //             'on_sale_end' => $product->on_sale_end,
    //         ];
    //     });
    //     return response()->json($data, Response::HTTP_OK);
    // }

    /**
     * PUT /on-sale 更新特定時間特價商品
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOnSaleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $product = Product::find($data['product_id']);
        if (!$product) {
            return response()->json([
                'message' => '找不到產品'
            ], Response::HTTP_NOT_FOUND);
        }
        $product->update($data);
        return response()->json([
            'message' => '更新成功',
            'data' => $product
        ], Response::HTTP_OK);
    }
}
