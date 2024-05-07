<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailVerifications;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMailable;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationController extends Controller
{
    /**
     * POST /send-verification-email 發送驗證信
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVerificationCode(Request $request): \Illuminate\Http\JsonResponse
    {
        //當前登入使用者
        $email = $request->user()->email;
        $emailVerification = new EmailVerifications();
        $code = rand(100000, 999999); //產生驗證碼
        $emailVerification->sendVerificationCode($email, $code); //寄送驗證碼
        $userName = $request->user()->name;
        // Mail::to('qaz326978547@gmail.com')->send(new VerificationCodeMailable($code));
        Mail::to($email)->queue(new VerificationCodeMailable($code, $userName));
        return response()->json([
            'message' => '驗證信已寄出'
        ], Response::HTTP_OK);
    }

    /**
     * POST /verify-verification-code 驗證驗證碼
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyVerificationCode(Request $request): \Illuminate\Http\JsonResponse
    {
        $message = [
            'code.required' => '驗證碼為必填欄位',
            'code.string' => '驗證碼必須為字串'
        ];
        $form = $request->validate([
            'code' => 'required|string'
        ], $message);
        $userId = $request->user()->id;
        $isVerified = EmailVerifications::verifyCode($userId, $form['code']);
        if ($isVerified) {
            return response()->json([
                'message' => '驗證成功'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => '驗證失敗',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
