<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository extends BaseRepository
{
    /**
     * SettingRepository constructor.
     * @param Setting $setting
     */
    public function __construct(Setting $setting)
    {
        $this->model = $setting;
    }

    /**
     * @return string
     */
    public function getProviderDomain(): string
    {
        $setting = $this->model->first();

        return $setting->provider_domain;
    }
}
