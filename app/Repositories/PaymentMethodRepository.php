<?php

namespace App\Repositories;

use App\Models\PaymentMethod;

class PaymentMethodRepository extends BaseRepository
{
    /**
     * PaymentMethodRepository constructor.
     * @param PaymentMethod $paymentMethod
     */
    public function __construct(PaymentMethod $paymentMethod)
    {
        $this->model = $paymentMethod;
    }
}
