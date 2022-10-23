<?php

namespace App\Repositories;

use App\Models\DomainType;

class DomainTypeRepository extends BaseRepository
{
    /**
     * DomainTypeRepository constructor.
     * @param DomainType $domainType
     */
    public function __construct(DomainType $domainType)
    {
        $this->model = $domainType;
    }
}
