<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartRepository extends BaseRepository
{
    /**
     * CartRepository constructor.
     * @param Cart $cart
     */
    public function __construct(Cart $cart)
    {
        $this->model = $cart;
    }

    /**
     * @return Cart
     */
    public function getCartByUser(): Cart
    {
        return $this->model->where('user_id', Auth::id())->first();
    }

    /**
     * @param array $data
     * @return array
     */
    public function addToCart(array $data): array
    {
        $cartCurrent = $this->getById($data['id']);

        if ($cartCurrent) {

            $cartItemCurrent = $cartCurrent->cartItems()->find($data['product_id']);

            if ($cartItemCurrent) {

                $cartItemCurrent->quantity = $cartItemCurrent->quantity + 1;
                $cartItemCurrent->total = $cartItemCurrent->quantity * $cartItemCurrent->price;
                $cartItemCurrent->save();

            } else {

                $cartCurrent->cartItems()->create([
                    'product_id' => $data['product_id'],
                    'quantity' => 1,
                    'total' => $data['product']->price
                ]);
            }

        } else {
            $cartCurrent = $this->model->updateOrCreate([
                'user_id' => Auth::id()
            ]);

            $cartCurrent->cartItems()->create([
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'],
                'price' => $data['price'],
                'total' => $data['quantity'] * $data['price'],
            ]);
        }

        return [
            'data' => $cartCurrent,
            'message' => 'Product added to cart',
        ];
    }

    /**
     *
     */
    public function resetCart(): void
    {
        $cart = $this->getCartByUser();

        if ($cart) {
            $cart->cartItems()->delete();
        }
    }
}
