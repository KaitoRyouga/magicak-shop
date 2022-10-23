<?php

namespace App\Repositories;

use App\Models\HostingPlanDiscount;

class HostingPlanDiscountRepository extends BaseRepository
{
    /**
     * HostingPlanDiscountRepository constructor.
     * @param HostingPlanDiscount $hostingPlanDiscount
     */
    public function __construct(HostingPlanDiscount $hostingPlanDiscount)
    {
        $this->model = $hostingPlanDiscount;
    }
}
