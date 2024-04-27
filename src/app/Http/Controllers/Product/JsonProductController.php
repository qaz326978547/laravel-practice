<?php

namespace App\Http\Controllers\Product;

// use Illuminate\Http\Response;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\ProductsRepositoryInterface;

class JsonProductController extends Controller
{
    private $product_repo;

    public function __construct(ProductsRepositoryInterface $product_repo)
    {
        $this->product_repo = $product_repo;
    }

    public function data(): JsonResponse
    {
        $products = $this->product_repo->findAll();
        return response()->json($products);
    }
}
