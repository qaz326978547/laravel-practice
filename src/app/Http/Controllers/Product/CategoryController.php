<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * GET /category 取得所有產品分類
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Category::all()
        ], Response::HTTP_OK);
    }
    /**
     * GET /category/{id} 取得單一產品分類
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Category::find($id);
        return response()->json([
            'data' => $data
        ], Response::HTTP_OK);
    }
    /**
     * POST /category 新增產品分類
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $message = [
            'name.required' => '請輸入產品分類名稱',
            'name.string' => '產品分類名稱必須為字串',
        ];
        $validator = Validator::make($data, [
            'name' => 'required|string',
        ], $message);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }
        Category::create($data);
        return response()->json([
            'data' => $data,
            'message' => '新增成功'
        ], Response::HTTP_CREATED);
    }
    /**
     * PUT /category/{id} 更新產品分類
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $message = [
            'name.required' => '請輸入產品分類名稱',
            'name.string' => '產品分類名稱必須為字串',
        ];
        $validator = Validator::make($data, [
            'name' => 'required|string',
        ], $message);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'message' => '找不到產品分類'
            ], Response::HTTP_NOT_FOUND);
        }
        $category->update($data);
        return response()->json([
            'data' => $data,
            'message' => '更新成功'
        ], Response::HTTP_OK);
    }
    /**
     * DELETE /category/{id} 刪除產品分類
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'message' => '找不到產品分類'
            ], Response::HTTP_NOT_FOUND);
        }
        $category->delete();
        return response()->json([
            'message' => '刪除成功'
        ], Response::HTTP_OK);
    }
}
