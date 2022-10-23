<?php

namespace App\Repositories;

use App\Models\TemplatePrice;

class TemplatePriceRepository extends BaseRepository
{
    /**
     * TemplatePriceRepository constructor.
     * @param TemplatePrice $templatePrice
     */
    public function __construct(TemplatePrice $templatePrice)
    {
        $this->model = $templatePrice;
    }

    /**
     * @param array $data
     */
    public function createTemplatePrice(array $data)
    {
        $this->updateOrCreate(null, $data);
    }

    /**
     * @param int $id
     * @param array $data
     */
    public function updateTemplatePrice(array $data, $id)
    {
        $this->model->where('template_id', $id)->update($data);
    }

    /**
     * @param int $id
     */
    public function deleteTemplatePrice(int $id)
    {
        $templatePrice = $this->model->where('template_id', $id)->first();
        if ($templatePrice) {
            $templatePrice->delete();
        }
    }
}
