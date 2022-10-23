<?php

namespace App\Managers;

use App\Repositories\HostingClusterRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Models\HostingCluster;

class HostingClusterManager extends BaseManager
{
    /**
     * @var HostingClusterRepository
     */
    protected $hostingClusterRepository;

    /**
     * HostingClusterManager constructor.
     * @param HostingClusterRepository $hostingClusterRepository
     */
    public function __construct(HostingClusterRepository $hostingClusterRepository)
    {
        $this->hostingClusterRepository = $hostingClusterRepository;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getHostingClusters(): LengthAwarePaginator
    {
        $listHostingClusters = $this->hostingClusterRepository->withoutActiveScope()->getHostingClusters();

        foreach ($listHostingClusters as $key => $hostingCluster) {
            $listHostingClusters[$key]['domain'] = $hostingCluster->systemDomain->domain_name;
            $listHostingClusters[$key]['location'] = $hostingCluster->dcLocation->location;
            $listHostingClusters[$key]['storage type'] = $hostingCluster->platform->name;
            $listHostingClusters[$key]['ip'] = $hostingCluster->systemDomain->ip;
        }

        return $listHostingClusters;
    }

    /**
     * @return Collection
     */
    public function getHostingClusterDropdowns(): Collection
    {
        return $this->hostingClusterRepository->getAll();
    }

    /**
     * @param array $data
     * @return HostingCluster
     */
    public function createHostingCluster(array $data): HostingCluster
    {
        return $this->hostingClusterRepository->createHostingCluster($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return HostingCluster
     */
    public function updateHostingCluster(int $id, array $data): HostingCluster
    {
        return $this->hostingClusterRepository->updateHostingCluster($id, $data);
    }

    /**
     * @param int $id
     */
    public function deleteHostingCluster(int $id)
    {
        $this->hostingClusterRepository->destroy($id);
    }
}
