<?php

namespace App\Repositories;

use App\Models\TemplateType;

class TemplateTypeRepository extends BaseRepository
{
    /**
     * TemplateTypeRepository constructor.
     * @param TemplateType $templateType
     */
    public function __construct(TemplateType $templateType)
    {
        $this->model = $templateType;
    }
}
