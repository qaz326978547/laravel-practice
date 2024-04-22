<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('v2')->group(function () {
    //登入、註冊 
    Route::prefix('auth')->group(function () {
        Route::post('signup', 'Auth\AuthController@signup');
        Route::post('login', 'Auth\AuthController@login')->name('login');
    });
    Route::get('/on-sale', 'Product\OnSaleController@index'); //取得特定時間特價商品
    Route::apiResource('/products', 'Product\ProductController')->only(['index', 'show']); //取得所有產品資料及單一產品資料 不需身份驗證
    Route::apiResource('/category', 'Product\CategoryController')->only(['index', 'show']); //取得所有產品分類資料及單一產品分類資料 不需身份驗證
    Route::apiResource('/image', 'Product\ImageController')->only(['index', 'show']); //取得所有產品圖片資料及單一產品圖片資料    
    //新增、修改、刪除產品資料 需要身份驗證
    Route::middleware(['auth:api', 'scopes:admin'])->prefix('admin')->group(function () {
        Route::put('/products/on-sale', 'Product\OnSaleController@update');
        Route::apiResource('/products', 'Product\ProductController')->only(['store', 'update', 'destroy']); //store update, destroy
        Route::apiResource('/products/category', 'Product\CategoryController')->only(['store', 'update', 'destroy']); //store update, destroy
        Route::apiResource('/products/image', 'Product\ImageController')->only(['store', 'update', 'destroy']); //store update, destroy
    });
});
