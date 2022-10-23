<?php

namespace App\Managers;

use App\Repositories\DataCenterLocationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Models\DataCenterLocation;

class DataCenterLocationManager extends BaseManager
{
    /**
     * @var DataCenterLocationRepository
     */
    protected $dataCenterLocationRepository;

    /**
     * DataCenterLocationManager constructor.
     * @param DataCenterLocationRepository $dataCenterLocationRepository
     */
    public function __construct(DataCenterLocationRepository $dataCenterLocationRepository)
    {
        $this->dataCenterLocationRepository = $dataCenterLocationRepository;
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function getLocationWithHostingPlanType(int $id): Collection
    {
        return $this->dataCenterLocationRepository->getLocationWithHostingPlanType($id);
    }

    /**
     * @param array $data
     * @return DataCenterLocation
     */
    public function createDataCenterLocation(array $data): DataCenterLocation
    {
        return $this->dataCenterLocationRepository->createDataCenterLocation($data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return DataCenterLocation
     */
    public function updateDataCenterLocation(array $data, int $id): DataCenterLocation
    {
        return $this->dataCenterLocationRepository->updateDataCenterLocation($data, $id);
    }

    /**
     * @param int $id
     */
    public function deleteDataCenterLocation(int $id)
    {
        $this->dataCenterLocationRepository->destroy($id);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function listDataCenterLocations(): LengthAwarePaginator
    {
        return $this->dataCenterLocationRepository->withoutActiveScope()->getLocations();
    }

    /**
     * @return Collection
     */
    public function listDataCenterLocationDropdowns(): Collection
    {
        return $this->dataCenterLocationRepository->getAll();
    }

    /**
     * @param int $id
     * @return DataCenterLocation
     */
    public function getDcLocationById(int $id): DataCenterLocation
    {
        return $this->dataCenterLocationRepository->getById($id);
    }
}
