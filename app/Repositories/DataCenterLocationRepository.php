<?php

namespace App\Repositories;

use App\Models\DataCenterLocation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DataCenterLocationRepository extends BaseRepository
{
    /**
     * DataCenterLocationRepository constructor.
     * @param DataCenterLocation $dataCenterLocation
     */
    public function __construct(DataCenterLocation $dataCenterLocation)
    {
        $this->model = $dataCenterLocation;
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function getLocationWithHostingPlanType(int $id): Collection
    {
        return $this->model
            ->whereHas('hosting', function ($query) use ($id) {
                $query->where('plan_type_id', $id);
            })->get();
    }

    /**
     * @param array $data
     * @return DataCenterLocation
     */
    public function createDataCenterLocation(array $data): DataCenterLocation
    {
        return $this->updateOrCreate(null, $data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return DataCenterLocation
     */
    public function updateDataCenterLocation(array $data, int $id): DataCenterLocation
    {
        return $this->updateOrCreate($id, $data);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getLocations(): LengthAwarePaginator
    {
        return $this->model->paginate();
    }
}
