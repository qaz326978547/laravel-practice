<?php

namespace App\Http\Controllers\Product;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ProductRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOnSaleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    /**
     * 取得所有商品 (可選擇是否只取得特價商品)
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $products = $this->productRepository->getAll();
        if ($request->query('is_on_sale') == 1) {
            $products = $this->productRepository->getOnSale();
        }

        return response()->json($products, Response::HTTP_OK);
    }
    /**
     * 取得單一商品
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $product = $this->productRepository->getById($id);
        return response()->json($product, Response::HTTP_OK);
    }
    /**
     * 新增商品
     *
     * @param  ProductRequest  $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        $product = $this->productRepository->create($data);
        return response()->json($product, Response::HTTP_CREATED);
        // ...
    }
    /**
     *  更新商品
     *
     * @param  ProductRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(ProductRequest $request, $id): JsonResponse
    {
        $data = $request->validated();
        $product = $this->productRepository->update($id, $data);
        return response()->json($product, Response::HTTP_OK);
    }

    /**
     * 刪除商品
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $product = $this->productRepository->delete($id);
        return response()->json($product, Response::HTTP_NO_CONTENT);
    }

    /**
     * 更新特價商品時間
     *
     * @param  UpdateOnSaleRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */

    public function updateOnSaleTime(UpdateOnSaleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $product = $this->productRepository->updateOnSaleTime($data['product_id'], $data);
        return response()->json($product, Response::HTTP_OK);
    }
    /**
     * 更新特價商品狀態
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function updateOnSaleStatus(Request $request): JsonResponse
    {
        $form = $request->all();
        $message = [
            'product_id.required' => '請輸入商品ID',
            'product_id.integer' => '商品ID必須為數字',
            'is_on_sale.required' => '請輸入特賣狀態',
            'is_on_sale.integer' => '特賣狀態必須為數字',
        ];
        $validator = Validator::make($form, [
            'product_id' => 'required|integer',
            'is_on_sale' => 'required|integer',
        ], $message);
        $product = $this->productRepository->updateOnSaleStatus($validator['product_id'], $validator['is_on_sale']);
        return response()->json($product, Response::HTTP_OK);
    }
}
