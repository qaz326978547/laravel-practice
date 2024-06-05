<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateOnSaleRequest;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class OnSaleController extends Controller
{
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    /**
     * GET /on-sale 取得所有當前特價商品
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        //使用filter()方法過濾出所有is_on_sale為1的商品 返回值為Collection 物件
        // 需注意 使用values()方法重新索引陣列 以符合JSON格式
        $data = $this->productRepository->getAll()->filter(function ($product) {
            return $product['is_on_sale'] == 1;
        })->values();
        return response()->json($data, Response::HTTP_OK);
    }
    /**
     * PUT /on-sale 更新特定時間特價商品
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOnSaleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $product = $this->productRepository->getById($data['product_id']);
        if (!$product) {
            return response()->json([
                'message' => '找不到產品'
            ], Response::HTTP_NOT_FOUND);
        }
        $product = $this->productRepository->update($data['product_id'], $data);
        return response()->json([
            'message' => '更新成功',
            'data' => $product
        ], Response::HTTP_OK);
    }
}
