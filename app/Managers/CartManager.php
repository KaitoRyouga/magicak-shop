<?php

namespace App\Managers;

use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;

class CartManager extends BaseManager
{
    /**
     * @var CartRepository
     */
    protected $cartRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * HostingManager constructor.
     * @param CartRepository $cartRepository
     * @param ProductRepository $productRepository
     *
     */
    public function __construct(
        CartRepository $cartRepository,
        ProductRepository $productRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @return array
     */
    public function getCart(): array
    {
        $data = $this->cartRepository->getCartByUser();

        return [
            'data' => $data,
            'message' => 'Get cart successfully.',
            'code' => 200
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function addToCart(array $data): array
    {
        $product = $this->productRepository->getProductById($data['product_id']);

        if ($product) {
            $cart = $this->cartRepository->addToCart([
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'price' => $product->price,
                'total' => $product->price * $data['quantity'],
            ]);

            if ($cart) {
                return [
                    'status' => true,
                    'message' => 'Product added to cart successfully',
                ];
            }
        }

        return [
            'data' => $product,
            'message' => 'Add to cart successfully.',
            'code' => 200
        ];
    }

    /**
     *
     */
    public function resetCart(): void
    {
        $this->cartRepository->resetCart();
    }
}
