<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * 指定資料表名稱
     *
     * @var array
     */
    protected $table = 'products'; //指定資料表名稱
    /**
     * 白名單
     *
     * @var array
     */
    protected $fillable = [
        'title', 'category', 'image', 'description', 'origin_price', 'price', 'quantity', 'is_enabled', 'unit'
    ];
}
