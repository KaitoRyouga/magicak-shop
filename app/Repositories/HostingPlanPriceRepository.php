<?php

namespace App\Repositories;

use App\Models\HostingPlanPrice;

class HostingPlanPriceRepository extends BaseRepository
{
    /**
     * HostingPlanPriceRepository constructor.
     * @param HostingPlanPrice $hostingPlanPrice
     */
    public function __construct(HostingPlanPrice $hostingPlanPrice)
    {
        $this->model = $hostingPlanPrice;
    }
}
