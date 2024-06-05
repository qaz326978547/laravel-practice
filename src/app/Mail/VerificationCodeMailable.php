<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EmailVerifications;

class VerificationCodeMailable extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $code;
    public $name;
    public $userId;
    public $email;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5; //最多重試5次

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code, $name, $userId, $email)
    {
        $this->code = $code;
        $this->name = $name;
        $this->userId = $userId;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // 故意引入一個錯誤
        // $this->nonexistentMethod();
        return $this->subject('Vogue傢俱信箱驗證')
            ->view(
                'emails.verification',
                ['code' => $this->code, 'name' => $this->name, 'userId' => $this->userId,'email'=>$this->email]
            );
    }
}
