<?php

namespace App\Repositories;

use App\Models\CartItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CartItemRepository extends BaseRepository
{
    /**
     * CartItemRepository constructor.
     * @param CartItem $dataCenterLocation
     */
    public function __construct(CartItem $dataCenterLocation)
    {
        $this->model = $dataCenterLocation;
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function getLocationWithCartType(int $id): Collection
    {
        return $this->model
            ->whereHas('hosting', function ($query) use ($id) {
                $query->where('plan_type_id', $id);
            })->get();
    }
}
