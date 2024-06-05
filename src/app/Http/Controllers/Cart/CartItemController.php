<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Interfaces\CartItemRepositoryInterface;
use App\Repositories\Interfaces\CartRepositoryInterface;

class CartItemController extends Controller
{
    protected $cartItemRepository;
    protected $cartRepository;

    public function __construct(CartItemRepositoryInterface $cartItemRepository, CartRepositoryInterface $cartRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
        $this->cartRepository = $cartRepository;
    }

    public function store(Request $request)
    {
        $form = $request->all();
        $this->validateQuantity($form);
        $cart = $this->cartRepository->getCartByUser(auth()->user());
        $cartItem = $this->cartItemRepository->getCartItemByProduct($cart, $form['product_id']);
        if (!empty($cartItem)) {
            $cartItem->quantity += $form['quantity'];
            $cartItem->save();
            $product = $cartItem->product()->get()->first();
            $product->quantity -= $form['quantity'];
            $product->save();
            return response()->json($cartItem, Response::HTTP_CREATED);
        } else {
            $cartItem = $this->cartItemRepository->createCartItem($form);
            return response()->json($cartItem, Response::HTTP_CREATED);
        }
    }

    public function update(Request $request, $id)
    {
        $form = $request->all();
        $this->validateQuantity($form);
        $cartItem = $this->cartItemRepository->findCartItem($id);
        if (empty($cartItem)) {
            return response()->json(['message' => '找不到購物車商品項目'], Response::HTTP_NOT_FOUND);
        }
        $product = Product::find($id);
        if ($product->quantity < $form['quantity']) {
            return response()->json(['message' => '庫存不足'], Response::HTTP_BAD_REQUEST);
        }
        $oldQuantity = $cartItem->quantity;
        $newQuantity = $form['quantity'];
        $this->cartItemRepository->updateCartItem($cartItem, ['quantity' => $newQuantity]);
        $quantityDiff = $newQuantity - $oldQuantity;
        if ($quantityDiff > 0) {
            $product->quantity -= $quantityDiff;
        } else {
            $product->quantity += $quantityDiff;
        }
        return response()->json([
            'message' => '更新成功',
            'data' => $cartItem
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $cartItem = $this->cartItemRepository->findCartItem($id);
        if (empty($cartItem)) {
            return response()->json(['message' => '找不到購物車商品項目'], Response::HTTP_NOT_FOUND);
        }
        $this->cartItemRepository->deleteCartItem($cartItem);
        return response()->json([
            'message' => '刪除成功',
        ], Response::HTTP_NO_CONTENT);
    }

    private function validateQuantity($form)
    {
        $message = [
            'quantity.required' => '請輸入數量',
            'quantity.integer' => '數量必須為整數',
            'quantity.min' => '數量最小為 1',
        ];
        $validator = Validator::make($form, [
            'quantity' => 'required|integer|min:1',
        ], $message);
        if ($validator->fails()) {
            $this->handleValidationFailure($validator->errors());
        }
    }

    private function handleValidationFailure($errors)
    {
        return response()->json($errors, Response::HTTP_BAD_REQUEST);
    }
}
