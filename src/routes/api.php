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
    // Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    // Route::post('register', 'Auth\RegisterController@register');
    Route::prefix('auth')->group(function () {
        Route::post('signup', 'Auth\AuthController@signup');
        Route::post('login', 'Auth\AuthController@login')->name('login');
        Route::post('logout', 'Auth\AuthController@logout')->middleware('auth:api'); //middleware('auth:api') 驗證使用者是否登入
        Route::prefix('email')->group(function () {
            Route::post('/send-verification-email', 'Auth\EmailVerificationController@sendVerificationCode');
            Route::post('/verify-verification-code', 'Auth\EmailVerificationController@verifyVerificationCode');
            Route::post('/resend-verification-email', 'Auth\EmailVerificationController@reSendVerificationEmail');
        });
    });
    Route::apiResource('/products', 'Product\ProductController')->only(['index', 'show']); //取得所有產品資料及單一產品資料 不需身份驗證
    Route::apiResource('/category', 'Product\CategoryController')->only(['index', 'show']); //取得所有產品分類資料及單一產品分類資料 不需身份驗證
    Route::apiResource('/image', 'Product\ImageController')->only(['index', 'show']); //取得所有產品圖片資料及單一產品圖片資料    
    //新增、修改、刪除產品資料 需要身份驗證
    Route::middleware(['auth:api', 'scopes:admin'])->prefix('admin')->group(function () {
        Route::put('/products/on-sale', 'Product\ProductController@updateOnSaleTime');
        Route::apiResource('/products', 'Product\ProductController')->only(['store', 'update', 'destroy']); //store update, destroy
        Route::apiResource('/products/category', 'Product\CategoryController')->only(['store', 'update', 'destroy']); //store update, destroy
        Route::apiResource('/products/image', 'Product\ImageController')->only(['store', 'update', 'destroy']); //store update, destroy
        Route::post('/aws/s3', 'Product\ImageController@storeAWSImage');
    });
    Route::middleware('auth:api')->group(function () {
        Route::apiResource('/cart', 'Cart\CartController')->only(['index']);
        Route::apiResource('/cart-item', 'Cart\CartItemController')->only(['store', 'update', 'destroy']);
    });
});
