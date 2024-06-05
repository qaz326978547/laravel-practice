<?php

namespace App\Repositories\Interfaces;

use App\Models\Cart\CartItem;
use App\Models\Cart\Cart;

interface CartItemRepositoryInterface
{
    public function getCartItemByProduct(Cart $cart, int $productId): ?CartItem;
    public function createCartItem(array $data): CartItem;

    public function findCartItem(int $id): ?CartItem;
    public function updateCartItem(CartItem $cartItem, array $data): bool;
    public function deleteCartItem(CartItem $cartItem): bool;
}
