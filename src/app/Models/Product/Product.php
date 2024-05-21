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
        'title', 'category_id', 'description', 'origin_price', 'price', 'quantity', 'is_enabled', 'unit', 'content', 'on_sale_start', 'on_sale_end', 'is_on_sale'
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
    /**
     * 關聯圖片 (多對多) 一個商品可以有多張圖片 (images表)
     */
    public function images()
    {
        return $this->belongsToMany(Image::class, 'products_images', 'product_id', 'image_id');
        //product_id 欄位對應到 products 表的 id 欄位，image_id 欄位對應到 images 表的 id 欄位。
    }

    public function getOnSale()
    {
        return $this->where('is_on_sale', 1);
    }
}
