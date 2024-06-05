<?php

namespace App\Http\Requests;

use App\Http\Requests\APIRequest;

class ImageRequest extends APIRequest
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
            'type' => ['required', 'string', 'in:main,sub'],
            'imageUrl' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'quantity.required' => '請輸入數量',
            'quantity.integer' => '數量必須為整數',
            'quantity.min' => '數量最小為 1',
        ];
    }
}
