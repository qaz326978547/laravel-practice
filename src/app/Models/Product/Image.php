<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * 指定資料表名稱
     *
     * @var array
     */
    protected $table = 'products_images'; //指定資料表名稱
    /**
     * 白名單
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'imageUrl', 'type'
    ];
    /**
     * 隱藏不必要的欄位
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
