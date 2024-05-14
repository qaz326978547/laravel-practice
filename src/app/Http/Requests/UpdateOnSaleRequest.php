<?php

namespace App\Http\Requests;

use App\Http\Requests\APIRequest;
use App\Rules\MinuteMustBeMultipleOf30;
use Carbon\Carbon;

class UpdateOnSaleRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|integer',
            'is_on_sale' => 'required|boolean',
            'on_sale_start' => ['required', 'date', 'before_or_equal:' . Carbon::now()->addMonth(1)->toDateTimeString(), new MinuteMustBeMultipleOf30()],
            'on_sale_end' => ['required', 'date', 'after:' . Carbon::now()->toDateTimeString(), 'before_or_equal:' . Carbon::now()->addMonth(1)->toDateTimeString(), new MinuteMustBeMultipleOf30()],
        ];
    }

    /**
     * Get the validation error messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_id.required' => '請輸入產品ID',
            'product_id.integer' => '產品ID必須為整數',
            'is_on_sale.required' => '請輸入是否特價',
            'is_on_sale.boolean' => '是否特價必須為布林值',
            'on_sale_start.required' => '請輸入特價開始時間',
            'on_sale_start.date' => '特價開始時間必須為日期格式',
            'on_sale_start.before_or_equal' => '特價開始時間不能超過一個月',
            'on_sale_end.required' => '請輸入特價結束時間',
            'on_sale_end.date' => '特價結束時間必須為日期格式',
            'on_sale_end.after' => '特價結束時間必須在特價開始時間之後',
            'on_sale_end.before_or_equal' => '特價結束時間不能超過一個月',
        ];
    }
}
