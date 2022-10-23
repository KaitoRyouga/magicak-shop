<?php

namespace App\Repositories;

use App\Models\TemplateDiscount;

class TemplateDiscountRepository extends BaseRepository
{
    /**
     * TemplateDiscountRepository constructor.
     * @param TemplateDiscount $templateDiscount
     */
    public function __construct(TemplateDiscount $templateDiscount)
    {
        $this->model = $templateDiscount;
    }
}
