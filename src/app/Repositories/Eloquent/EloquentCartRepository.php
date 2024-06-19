<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart\Cart;
use App\Repositories\Interfaces\CartRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentCartRepository implements CartRepositoryInterface
{
    private $cart_model;
    public function __construct(Cart $cart_model)
    {
        $this->cart_model = $cart_model;
    }
    public function getCartByUser($user): ?Cart
    {
        return  $this->cart_model->where('user_id', $user->id)->with(['cartItems.product.category', 'cartItems.product.images'])->first();
    }

    public function createCart($user): Cart
    {
        return  $this->cart_model->create([
            'user_id' => $user->id,
            'total_price' => 0
        ]);
    }

    public function getCartItems(Cart $cart): Collection
    {
        return $cart->cartItems->map(function ($cartItem) {
            $product = $cartItem->product;
            return [
                'id' => $product->id,
                'title' => $product->title,
                'category' => $product->category->name,
                'description' => $product->description,
                'content' => $product->content,
                'origin_price' => $product->origin_price,
                'price' => $product->price,
                'quantity' => $cartItem->quantity,
                'is_enabled' => $product->is_enabled,
                'unit' => $product->unit,
                'image' => $product->images->where('type', 'main')->first()->imageUrl ?? null,
                'images' => $product->images->where('type', 'sub')->pluck('imageUrl') ?? [],
                'is_on_sale' => $product->is_on_sale,
                'total_price' => $product->price * $cartItem->quantity,
            ];
        });
    }

    public function calculateTotalPrice(Collection $cartItems): float
    {
        return $cartItems->reduce(function ($carry, $item) {
            return $carry + $item['price'] * $item['quantity'];
        }, 0);
    }
}
