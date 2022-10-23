<?php

namespace App\Managers;

use App\Models\Product;
use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductManager extends BaseManager
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var CartManager
     */
    protected $cartManager;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var SettingManager
     */
    protected $settingManager;

    /**
     * @var ProductValidator
     */
    protected $productValidator;

    /**
     * ProductManager constructor.
     * @param UserRepository $userRepository
     * @param ProductRepository $productRepository
     * @param CartManager $cartManager
     * @param UserManager $userManager
     * @param SettingManager $settingManager,
     */
    public function __construct(
        UserRepository $userRepository,
        ProductRepository $productRepository,
        CartManager $cartManager,
        SettingManager $settingManager
    ) {
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        $this->cartManager = $cartManager;
        $this->settingManager = $settingManager;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getProducts(): LengthAwarePaginator
    {
        return $this->productRepository->getProducts();
    }

    /**
     * @param int $id
     * @return Product|null
     */
    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->getProductById($id);
    }
}
