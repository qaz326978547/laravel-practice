<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart\CartItem;
use App\Models\Cart\Cart;
use App\Repositories\Interfaces\CartItemRepositoryInterface;

class EloquentCartItemRepository implements CartItemRepositoryInterface
{
    public function getCartItemByProduct(Cart $cart, int $productId): ?CartItem
    {
        return $cart->cartItems()->where('product_id', $productId)->first();
    }

    public function createCartItem(array $data): CartItem
    {
        return CartItem::create($data);
    }

    public function findCartItem(int $id): ?CartItem
    {
        return CartItem::find($id);
    }
    public function updateCartItem(CartItem $cartItem, array $data): bool
    {
        return $cartItem->update($data);
    }

    public function deleteCartItem(CartItem $cartItem): bool
    {
        return $cartItem->delete();
    }
}
