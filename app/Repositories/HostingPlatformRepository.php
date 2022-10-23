<?php

namespace App\Repositories;

use App\Models\HostingPlatform;

class HostingPlatformRepository extends BaseRepository
{
    /**
     * HostingPlatformRepository constructor.
     * @param HostingPlatform $hostingPlatform
     */
    public function __construct(HostingPlatform $hostingPlatform)
    {
        $this->model = $hostingPlatform;
    }
}
