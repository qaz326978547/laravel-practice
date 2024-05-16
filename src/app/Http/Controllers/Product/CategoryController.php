<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\CategoryRepositoryInterface;
class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * GET /category 取得所有產品分類
     *
     * @return \Illuminate\Http\JsonResponse;
     */
    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->getAll();
        return response()->json([
            'data' => $categories
        ], Response::HTTP_OK);
    }
    /**
     * GET /category/{id} 取得單一產品分類
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse;
     */
    public function show($id)
    {
        $category = $this->categoryRepository->getById($id);
        if (!$category) {
            return response()->json([
                'message' => '找不到產品分類'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'data' => $category
        ], Response::HTTP_OK);
    }
    /**
     * POST /category 新增產品分類
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse;
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
        $this->categoryRepository->create($data);
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
     * @return \Illuminate\Http\JsonResponse;
     */
    public function update(Request $request, $id) : JsonResponse
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
        $category = $this->categoryRepository->getById($id);
        if (!$category) {
            return response()->json([
                'message' => '找不到產品分類'
            ], Response::HTTP_NOT_FOUND);
        }
        $this->categoryRepository->update($id, $data);
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
        $category = $this->categoryRepository->getById($id);
        if (!$category) {
            return response()->json([
                'message' => '找不到產品分類'
            ], Response::HTTP_NOT_FOUND);
        }
        $this->categoryRepository->delete($id);
        return response()->json([
            'message' => '刪除成功'
        ], Response::HTTP_OK);
    }
}
