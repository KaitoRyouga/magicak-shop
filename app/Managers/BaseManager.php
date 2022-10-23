<?php

namespace App\Managers;

use App\Traits\CustomException;

abstract class BaseManager
{
    use CustomException;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
}
