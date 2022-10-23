<?php

namespace App\Validators;

use App\Traits\CustomException;

abstract class BaseValidator
{
    use CustomException;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
}
