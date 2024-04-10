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
    Route::prefix('user')->group(function () {
        Route::post('signup', 'AuthController@signup');
        Route::post('login', 'AuthController@login')->name('login');
    });
    //取得所有產品資料及單一產品資料 不需身份驗證
    Route::apiResource('products', 'ProductController')->only(['index', 'show']);
    //新增、修改、刪除產品資料 需要身份驗證
    Route::prefix('admin')->group(function () {
        Route::middleware(['auth:api', 'scopes:admin'])->group(function () {
            Route::apiResource('products', 'ProductController')->only(['store', 'update', 'destroy']); //store update, destroy
        });
    });
});
