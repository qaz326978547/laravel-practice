<?php

namespace App\Http\Requests;

class ProductRequest extends APIRequest
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
            'title' => ['required', 'string'],
            'category_id' => ['required', 'integer'],
            'content' => ['nullable', 'string'],
            'description' => ['required', 'string'],
            'origin_price' => ['required', 'numeric', 'min:50', 'max:100000'],
            'price' => ['required', 'numeric', 'min:50', 'max:100000'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000'],
            'is_enabled' => ['required', 'integer'],
            'unit' => ['required', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['integer'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '請輸入標題',
            'category_id.required' => '請輸入分類',
            'description.required' => '請輸入描述',
            'origin_price.required' => '請輸入原價',
            'price.required' => '請輸入售價',
            'quantity.required' => '請輸入數量',
            'is_enabled.required' => '請輸入是否上架',
            'unit.required' => '請輸入單位',
            'images.array' => '圖片格式錯誤',
            'images.*.integer' => '圖片格式錯誤',
        ];
    }
}
