<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

//中間表
class ProductImage extends Model
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
        'product_id', 'image_id'
    ];
    /**
     * 隱藏不必要的欄位
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    //一個圖片只能對應到一個商品
    public function product()
    {
        return $this->belongsTo(Product::class); //products_images表的product_id欄位對應到products表的id欄位
    }
    //一個商品可以有多個圖片
    public function image()
    {
        return $this->belongsTo(Image::class); //products_images表的image_id欄位對應到images表的id欄位
    }
}
