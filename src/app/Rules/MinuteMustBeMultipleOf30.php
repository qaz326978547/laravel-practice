<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
// use DateTime;

use DateTime;

class MinuteMustBeMultipleOf30 implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $format = 'Y-m-d H:i:s';
        $dateTime = DateTime::createFromFormat($format, $value); //將$value轉換成DateTime物件

        if ($dateTime === false) { //如果轉換失敗，回傳false
            return false;
        }

        $formattedDate = $dateTime->format($format); //將DateTime物件轉換成指定格式的字串

        if ($formattedDate !== $value) { //如果轉換後的字串與原本的字串不同，回傳false
            return false;
        }

        $date = Carbon::createFromFormat($format, $value);
        return $date->minute % 30 === 0;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '日期格式必須是Y-m-d H:i:s，且時間必須為整點或30分';
    }
}
