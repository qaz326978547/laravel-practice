<?php

namespace App\Models\Cart;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;

class CartItem extends Model
{
    /**
     * 指定資料表名稱
     *
     * @var array
     */
    protected $table = 'cart_items';
    /**
     * 白名單
     *
     * @var array
     */
    protected $fillable = ['cart_id', 'product_id', 'quantity'];
    /**
     * 一個商品項目只能對應到一個購物車
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * 一個商品項目只能對應到一個商品
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
