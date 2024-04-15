<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Category;

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
        'title', 'category_id', 'description', 'origin_price', 'price', 'quantity', 'is_enabled', 'unit','content'
    ];
    /**
     * 隱藏不必要的欄位
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    /**
     * 關聯分類
     */
    public function category()
    {
        return $this->belongsTo(Category::class); //products表的category_id欄位對應到products_category表的id欄位
    }
}
