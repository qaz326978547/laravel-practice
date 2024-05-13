<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class EmailVerifications extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_verifications';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'code', 'expired_at', 'is_verified', 'user_id'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class); //User的id對應到email_verifications的user_id
    }

    public function sendVerificationCode($email, $code)
    {
        $emailVerification = EmailVerifications::firstOrNew(['email' => $email]); //如果有資料就取得，沒有就新增
        $emailVerification->code =  Hash::make($code); //將驗證碼加密
        $emailVerification->user_id = auth()->id(); //取得當前登入使用者的id
        $emailVerification->expired_at = now()->addMinutes(10); //驗證碼過期時間為10分鐘後
        $emailVerification->save();
    }

    public static function verifyCode($userId, $code)
    {
        $verification = self::where('user_id', $userId)->first(); //取得使用者的驗證資料 使用self可以直接呼叫 EmailVerifications::verifyCode
        //將加密後的驗證碼與資料庫的驗證碼比對 如果驗證碼已過期則回傳false
        if ($verification && Hash::check($code, $verification->code) && $verification->expired_at->lt(now())) {
            // 驗證成功
            $verification->is_verified = 1; //將驗證狀態改為已驗證
            $verification->save();
            return true;
        } else {
            // 驗證失敗
            return false;
        }
    }
}
