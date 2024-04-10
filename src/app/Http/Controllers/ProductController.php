<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Response;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response; //使用於狀態碼

class ProductController extends Controller
{
    /**
     * GET /products 取得所有產品
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        Product::all();
        return response()->json([
            'data' => Product::all()
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
        $data = Product::find($id);
        return response()->json([
            'data' => $data
        ], 200);
    }
    /**
     * POST /products 新增產品
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->all();
        $messages = [
            'required.title' => '請輸入標題',
            'required.category' => '請輸入分類',
            'required.image' => '請輸入單張封面圖片',
            'required.description' => '請輸入描述',
            'required.origin_price' => '請輸入原價',
            'required.price' => '請輸入售價',
            'required.quantity' => '請輸入數量',
            'required.is_enabled' => '請輸入是否上架',
            'required.unit' => '請輸入單位',
        ];
        $validator = Validator::make($data, [
            'title' => ['required', 'string'],
            'category' => ['required', 'string'],
            'image' => ['required', 'string'],
            'images' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'description' => ['required', 'string'],
            'origin_price' => ['required', 'numeric', 'min:50', 'max:100000'],
            'price' => ['required', 'numeric', 'min:50', 'max:100000'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000'],
            'is_enabled' => ['required', 'integer'],
            'unit' => ['required', 'string'],
        ], $messages);

        //驗證失敗拋出錯誤
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }
        Product::create($data);
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
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->all();
        $messages = [
            'required.title' => '請輸入標題',
            'required.category' => '請輸入分類',
            'required.image' => '請輸入單張封面圖片',
            'required.description' => '請輸入描述',
            'required.origin_price' => '請輸入原價',
            'required.price' => '請輸入售價',
            'required.quantity' => '請輸入數量',
            'required.is_enabled' => '請輸入是否上架',
            'required.unit' => '請輸入單位',
        ];
        $validator = Validator::make($data, [
            'title' => ['required', 'string'],
            'category' => ['required', 'string'],
            'image' => ['required', 'string'],
            'images' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'description' => ['required', 'string'],
            'origin_price' => ['required', 'numeric', 'min:50', 'max:100000'],
            'price' => ['required', 'numeric', 'min:50', 'max:100000'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000'],
            'is_enabled' => ['required', 'integer'],
            'unit' => ['required', 'string'],
        ], $messages);

        //驗證失敗拋出錯誤
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }
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
