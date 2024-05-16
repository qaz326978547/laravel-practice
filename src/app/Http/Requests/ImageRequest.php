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
            'type.required' => '請輸入類型',
            'type.in' => '類型只能填入main或sub',
            'imageUrl.required' => '請輸入圖片網址',
            'imageUrl.string' => '圖片網址必須為字串',
        ];
    }
}
