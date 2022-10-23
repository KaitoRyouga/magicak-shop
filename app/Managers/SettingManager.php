<?php

namespace App\Managers;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\SettingRepository;

class SettingManager extends BaseManager
{
    /**
     * @var SettingRepository
     */
    protected $settingRepository;

    /**
     * SettingManager constructor.
     * @param SettingRepository $settingRepository
     */
    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * @return Setting
     */
    public function getFirst(): Setting
    {
        return $this->settingRepository->getFirst();
    }


    /**
     * @return string
     */
    public function getProviderDomain(): string
    {
        return $this->settingRepository->getProviderDomain();
    }
}
