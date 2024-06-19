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
            // 'imageUrl' => ['required', 'string'],
            'file' => ['required', 'image'],
        ];
    }

    public function messages()
    {
        return [
            'type.required' => '請輸入圖片類型',
            'type.string' => '圖片類型必須為字串',
            'type.in' => '圖片類型必須是 main 或 sub',
            // 'imageUrl.required' => '請輸入圖片路徑',
            // 'imageUrl.string' => '圖片路徑必須為字串',
            'file.required' => '請上傳圖片',
        ];
    }
}
