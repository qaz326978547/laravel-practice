<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;
use App\Models\Cart\Cart;

interface CartRepositoryInterface
{
    public function getCartByUser($user): ?Cart;
    public function createCart($user): Cart;
    public function getCartItems(Cart $cart): Collection;
    public function calculateTotalPrice(Collection $cartItems): float;
}
