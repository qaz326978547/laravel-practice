<?php

namespace App\Http\Controllers\Product;

use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ProductRequest;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = $this->productRepository->getAll();
        return response()->json($products, Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
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
     * Store a newly created resource in storage.
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
     * Update the specified resource in storage.
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

    public function destroy($id): JsonResponse
    {
        $product = $this->productRepository->delete($id);
        return response()->json($product, Response::HTTP_NO_CONTENT);
    }
}