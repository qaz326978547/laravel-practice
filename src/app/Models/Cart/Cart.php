<?php

namespace App\Models\Cart;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Cart extends Model
{
    /**
     * 指定資料表名稱
     *
     * @var array
     */
    protected $table = 'carts';
    /**
     * 白名單
     *
     * @var array
     */
    protected $fillable = ['user_id'];
    /**
     * 一個購物車可以有多個商品項目
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    /**
     * 一個購物車只能對應到一個使用者
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
