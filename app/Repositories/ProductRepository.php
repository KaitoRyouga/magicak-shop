<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository extends BaseRepository
{
    /**
     * ProductRepository constructor.
     * @param Product $product
     *
     */
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getProducts(): LengthAwarePaginator
    {
        return $this->model->orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * @param int $id
     * @return Product|null
     */
    public function getProductById(int $id): ?Product
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * @param array $data
     */
    public function deleteProduct(array $data): void
    {
        $product = $this->getProductById($data['id']);

        if ($product) {
            $product->delete();
        }
    }
}
