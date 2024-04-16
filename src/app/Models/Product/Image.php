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
    protected $table = 'images'; //指定資料表名稱
    /**
     * 白名單
     *
     * @var array
     */
    protected $fillable = [
        'imageUrl', 'type'
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
     * 關聯商品 (多對多) 一張圖片可以對應到多個商品 (products表)
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_images', 'image_id', 'product_id'); //images表的id欄位對應到products_images表的image_id欄位
    }
    /**
     * 關聯商品 (一對多) 一張圖片可以對應到多個商品 (products_images表) 中間表
     */
    public function productImages()
    {
        return $this->hasMany(ProductImage::class); //images表的id欄位對應到products_images表的image_id欄位
    }
}
