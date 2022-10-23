<?php

namespace App\Repositories;

use App\Models\UserPayment;

class UserPaymentRepository extends BaseRepository
{
    /**
     * UserPaymentRepository constructor.
     * @param UserPayment $userPayment
     */
    public function __construct(UserPayment $userPayment)
    {
        $this->model = $userPayment;
    }
}
