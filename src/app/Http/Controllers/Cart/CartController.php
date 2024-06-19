<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CartRepositoryInterface;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function index()
    {
        $user = auth()->user();
        $cart = $this->cartRepository->getCartByUser($user);
        if (empty($cart)) {
            $cart = $this->cartRepository->createCart($user);
        }
        $cartItems = $this->cartRepository->getCartItems($cart);
        $totalPrice = $this->cartRepository->calculateTotalPrice($cartItems);

        $cartData = [
            'cart_id' => $cart->id,
            'total_price' => $totalPrice,
            'cart_items' => $cartItems
        ];
        return response()->json($cartData);
    }
}
