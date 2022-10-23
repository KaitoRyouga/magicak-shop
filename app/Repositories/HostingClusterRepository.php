<?php

namespace App\Repositories;

use App\Models\HostingCluster;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class HostingClusterRepository extends BaseRepository
{
    /**
     * HostingClusterRepository constructor.
     * @param HostingCluster $hostingCluster
     */
    public function __construct(HostingCluster $hostingCluster)
    {
        $this->model = $hostingCluster;
    }

    /**
     * @param array $data
     * @return HostingCluster
     */
    public function createHostingCluster(array $data): HostingCluster
    {
        return $this->updateOrCreate(null, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return HostingCluster
     */
    public function updateHostingCluster(int $id, array $data): HostingCluster
    {
        return $this->updateOrCreate($id, $data);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getHostingClusters(): LengthAwarePaginator
    {
        return $this->model->with('SystemDomain')->paginate();
    }

    /**
     * @param array $hosting_cluster_ids
     * @return array
     */
    public function getLocationIdsByClusterIds(array $hosting_cluster_ids): array
    {
        return $this->model->whereIn('id', $hosting_cluster_ids)->pluck('dc_location_id')->toArray();
    }
}
