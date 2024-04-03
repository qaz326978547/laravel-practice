<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //
    public function getProducts()
    {
        return response()->json([
            'data' => 'OK'
        ], 200);
    }

    public function addProduct(Request $request)
    {
        $data = $request->all();

        $messages = [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
        ];
        $validator = Validator::make($data, [
            'name' => 'required|string',
        ], $messages);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }
        return response()->json([
            'data' => $data
        ], 200);
    }
}
