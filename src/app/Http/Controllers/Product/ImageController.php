<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Image;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Interfaces\ImageRepositoryInterface;
use App\Http\Requests\ImageRequest;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    protected $imageRepository;

    public function __construct(ImageRepositoryInterface $imageRepositoryInterface)
    {
        $this->imageRepository = $imageRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->imageRepository->getAll()
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $data = $this->imageRepository->getById($id);
        if (!$data) {
            return response()->json([
                'message' => '找不到圖片'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'data' => $data
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  ImageRequest  $request
     * @return JsonResponse
     */
    public function store(ImageRequest $request): JsonResponse
    {
        $data = $request->validated();
        $image = $this->imageRepository->create($data);
        return response()->json([
            'data' => [
                'id' => $image->id,
                'type' => $image->type,
                'imageUrl' => $image->imageUrl
            ],
            'message' => '新增成功'
        ], Response::HTTP_CREATED);
    }
    public function update(ImageRequest $request, $id)
    {
        $data = $request->validated();
        $image = $this->imageRepository->getById($id);
        if (!$image) {
            return response()->json([
                'message' => '找不到圖片'
            ], Response::HTTP_NOT_FOUND);
        }
        $this->imageRepository->update($id, $data);
        return response()->json([
            'data' => $data,
            'message' => '更新成功'
        ], Response::HTTP_OK);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $image = $this->imageRepository->getById($id);
        if (!$image) {
            return response()->json([
                'message' => '找不到圖片'
            ], Response::HTTP_NOT_FOUND);
        }
        $this->imageRepository->delete($id);
        return response()->json([
            'message' => '刪除成功'
        ], Response::HTTP_OK);
    }
}
