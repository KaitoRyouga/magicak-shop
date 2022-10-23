<?php

namespace App\Repositories;

use App\Models\HostingPlanType;

class HostingPlanTypeRepository extends BaseRepository
{
    /**
     * HostingPlanTypeRepository constructor.
     * @param HostingPlanType $hostingPlanType
     */
    public function __construct(HostingPlanType $hostingPlanType)
    {
        $this->model = $hostingPlanType;
    }
}
