<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * 指定資料表名稱
     *
     * @var array
     */
    protected $table = 'products_categories'; //指定資料表名稱
    /**
     * 白名單
     *
     * @var array
     */
    protected $fillable = [
        'name'
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
