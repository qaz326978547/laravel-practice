<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUser;
use App\User;
use Illuminate\Support\Facades\Auth; //驗證
use Symfony\Component\HttpFoundation\Response; //使用於狀態碼
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * POST /signup 註冊
     *
     * @param  CreateUser $request
     * @return \Illuminate\Http\Response
     */

    public function signup(CreateUser $request): \Illuminate\Http\JsonResponse
    {
        $form = $request->validated();

        $user = new User([
            'name' => $form['name'],
            'email' => $form['email'],
            'password' => bcrypt($form['password'])

        ]);
        if (isset($form['is_admin'])) {
            $user->is_admin = $form['is_admin'];
        }

        $user->save();
        return response()->json([
            'data' => $form,
            'message' => '註冊成功'
        ], Response::HTTP_CREATED); //201為新增成功的狀態碼
    }

    /**
     * POST /login 登入
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $form = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        if (!Auth::attempt($form)) { //驗證使用者是否存在
            return response()->json([
                'message' => '帳號或密碼錯誤'
            ], Response::HTTP_UNAUTHORIZED); //401為未授權的狀態碼
        }
        $user = $request->user(); //取得使用者資料
        $token = $user->createToken('Token', ['admin'])->accessToken; //建立token
        return response()->json([
            'token' => $token, //回傳token
        ], Response::HTTP_OK); //200為成功的狀態碼
    }

    public function scopeAdmin()
    {
        $admin = User::where('is_admin', 1)->get();
        return response()->json([
            'data' => $admin
        ], Response::HTTP_OK);
    }
}
