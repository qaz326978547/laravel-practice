<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth; //驗證
use Symfony\Component\HttpFoundation\Response; //使用於狀態碼
use App\Http\Controllers\Controller;
use App\Models\EmailVerifications;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMailable;
use Illuminate\Support\Facades\Hash;

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
            'password' => Hash::make($form['password'])
        ]);
        if (isset($form['is_admin'])) {
            $user->is_admin = $form['is_admin'];
        }
        $user->save();

        $userId = $user->id;
        $email = $form['email'];
        $emailVerification = new EmailVerifications();
        $code = random_int(100000, 999999); //產生驗證碼
        $emailVerification->sendVerificationCode($email, $code, $userId); //寄送驗證碼
        $userName = $form['name'];
        Mail::to($email)->queue(new VerificationCodeMailable($code, $userName, $userId, $email));
        return response()->json([
            'data' => $form,
            'message' => '已註冊成功，請至信箱收取驗證信件'
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

    public function scopeAdmin() //取得所有管理員
    {
        $admin = User::where('is_admin', 1)->get();
        return response()->json([
            'data' => $admin
        ], Response::HTTP_OK);
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->token()->revoke(); //撤銷token
        return response()->json([
            'message' => '登出成功'
        ], Response::HTTP_OK);
    }
}
