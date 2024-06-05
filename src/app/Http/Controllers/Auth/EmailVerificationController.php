<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailVerifications;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMailable;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class EmailVerificationController extends Controller
{
    /**
     * POST /send-verification-email 發送驗證信
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reSendVerificationEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $email = $request->query('email'); // 從請求中獲取電子郵件地址

        // 找到對應的使用者
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => '電子郵件地址不正確'
            ], Response::HTTP_NOT_FOUND); // 404為找不到資源的狀態碼
        }
        //撤銷token 及 驗證碼
        foreach ($user->tokens as $token) {
            $token->revoke();
        }
        $user->emailVerification->delete();
        // 產生新的驗證碼並寄送驗證郵件
        $emailVerification = new EmailVerifications();
        $code = random_int(100000, 999999); //產生驗證碼
        $emailVerification->sendVerificationCode($email, $code, $user->id); //寄送驗證碼
        Mail::to($email)->queue(new VerificationCodeMailable($code, $user->name, $user->id, $email));

        return response()->json([
            'message' => '驗證郵件已重新寄送'
        ], Response::HTTP_OK); // 200為請求成功的狀態碼
    }

    /**
     * POST /verify-verification-code 驗證驗證碼
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function verifyVerificationCode(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'code' => 'required|integer'
        ]);
        $userId = $request->query('user_id');
        $code = $request->query('code');
        $isVerified = EmailVerifications::verifyCode($userId, $code);
        if ($isVerified) { //驗證碼正確 由前端決定導向頁面 並取得token登入
            $user = User::find($userId);
            $token = $user->createToken('Token', ['admin'])->accessToken; //建立token
            return response()->json([
                'token' => $token, //回傳token
            ], Response::HTTP_OK); //200為成功的狀態碼
        } else {
            return response()->json([
                'message' => '驗證碼錯誤'
            ], Response::HTTP_UNAUTHORIZED); //401為未授權的狀態碼
        }
    }
}
