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
     *  將屬性轉換為常見的資料型態
     *
     * @var array
     */
    protected $dates = ['expired_at'];
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

    public function sendVerificationCode($email, $code, $userId)
    {
        $emailVerification = EmailVerifications::firstOrNew(['email' => $email]); //如果有資料就取得，沒有就新增

        // 檢查上次發送驗證碼的時間是否在 30 秒前
        if ($emailVerification->exists && $emailVerification->updated_at->gt(now()->subSeconds(30))) {
            throw new \Exception('請等待 30 秒後再試');
        }
        $emailVerification->code =  Hash::make($code); //將驗證碼加密
        $emailVerification->user_id = $userId; //取得當前登入使用者的id
        $emailVerification->expired_at = now()->addMinutes(10); //驗證碼過期時間為10分鐘後
        $emailVerification->save();
    }

    public static function verifyCode($userId, $code)
    {
        $verification = self::where('user_id', $userId)->first(); //取得使用者的驗證資料 使用self可以直接呼叫 EmailVerifications::verifyCode

        // 驗證碼是否正確且未過期
        if ($verification && Hash::check($code, $verification->code) && now()->lt($verification->expired_at)) {
            // 驗證成功
            $verification->is_verified = 1;
            $verification->save();
            return true;
        } elseif ($verification) {
            // 驗證碼已過期
            throw new \Exception('驗證碼已過期');
        }

        // 如果找不到驗證資料，返回 false
        throw new \Exception('找不到驗證資料');
    }
}
