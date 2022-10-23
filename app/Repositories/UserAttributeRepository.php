<?php

namespace App\Repositories;

use App\Models\UserAttribute;
use Illuminate\Database\Eloquent\Collection;

class UserAttributeRepository extends BaseRepository
{
    /**
     * UserAttributeRepository constructor.
     * @param UserAttribute $userAttribute
     */
    public function __construct(UserAttribute $userAttribute)
    {
        $this->model = $userAttribute;
    }

    /**
     * @return Collection
     */
    public function getAllAttributes(): Collection
    {
        return $this->getAll();
    }
}
