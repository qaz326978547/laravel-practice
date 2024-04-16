<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Image;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    //
    public function index()
    {
        return response()->json([
            'data' => Image::all()
        ]);
    }
    public function show($id)
    {
        $data = Image::find($id);
        return response()->json([
            'data' => $data
        ]);
    }
    public function store(Request $request)
    {
        $data = $request->all();
        $message = [
            'type.required' => '請輸入類型',
            'type.in' => '類型只能填入main或sub',
            'imageUrl.required' => '請輸入圖片網址',
            'imageUrl.string' => '圖片網址必須為字串',
        ];
        $validator = Validator::make($data, [
            'type' => 'required|in:main,sub', //type只能填入main或sub
            'imageUrl' => 'required|string',
        ], $message);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        Image::create($data);
        //在data中加入id
        $data['id'] = Image::all()->last()->id;
        return response()->json([
            'data' => [
                'id' => $data['id'],
                'type' => $data['type'],
                'imageUrl' => $data['imageUrl']
            ],
            'message' => '新增成功'
        ], Response::HTTP_CREATED);
    }
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $message = [
            'type.required' => '請輸入類型',
            'type.in' => '類型只能填入main或sub',
            'imagerUrl.required' => '請輸入圖片網址',
            'imagerUrl.string' => '圖片網址必須為字串',
        ];
        $validator = Validator::make($data, [
            'type' => 'required|in:main,sub', //type只能填入main或sub
            'imagerUrl' => 'required|string',
        ], $message);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        $image = Image::find($id);
        $image->update($data);
        return response()->json([
            'data' => $data,
            'message' => '更新成功'
        ], Response::HTTP_OK);
    }
    public function destroy($id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json([
                'message' => '找不到圖片'
            ], Response::HTTP_NOT_FOUND);
        }
        $image->delete();
        return response()->json([
            'message' => '刪除成功'
        ], Response::HTTP_OK);
    }
}
