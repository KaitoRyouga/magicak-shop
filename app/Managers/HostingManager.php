<?php

namespace App\Managers;

use App\Models\HostingPlan;
use App\Repositories\HostingPlanDiscountRepository;
use App\Repositories\HostingPlanPriceRepository;
use App\Repositories\HostingPlanRepository;
use App\Repositories\HostingPlanTypeRepository;
use App\Repositories\HostingPlatformRepository;
use App\Repositories\HostingClusterRepository;
use App\Repositories\UserWebsiteRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HostingManager extends BaseManager
{

    const HOSTING_PLAN_WEB_FREE = 1;
    const HOSTING_PLAN_BUSINESS_FREE = 5;
    const ONE_MONTH = 1;
    const SIX_MONTHS = 6;
    const ONE_YEAR = 12;

    /**
     * @var HostingPlanDiscountRepository
     */
    protected $hostingPlanDiscountRepository;

    /**
     * @var HostingPlanPriceRepository
     */
    protected $hostingPlanPriceRepository;

    /**
     * @var HostingPlanRepository
     */
    protected $hostingPlanRepository;

    /**
     * @var HostingPlanTypeRepository
     */
    protected $hostingPlanTypeRepository;

    /**
     * @var HostingPlatformRepository
     */
    protected $hostingPlatformRepository;

    /**
     * @var HostingClusterRepository
     */
    protected $hostingClusterRepository;

    /**
     * @var UserWebsiteRepository
     */
    protected $userWebsiteRepository;

    /**
     * HostingManager constructor.
     * @param HostingPlanDiscountRepository $hostingPlanDiscountRepository
     * @param HostingPlanPriceRepository $hostingPlanPriceRepository
     * @param HostingPlanRepository $hostingPlanRepository
     * @param HostingPlanTypeRepository $hostingPlanTypeRepository
     * @param HostingPlatformRepository $hostingPlatformRepository,
     * @param UserWebsiteRepository $userWebsiteRepository
     * @param HostingClusterRepository $hostingClusterRepository
     *
     */
    public function __construct(
        HostingPlanDiscountRepository $hostingPlanDiscountRepository,
        HostingPlanPriceRepository $hostingPlanPriceRepository,
        HostingPlanRepository $hostingPlanRepository,
        HostingPlanTypeRepository $hostingPlanTypeRepository,
        HostingPlatformRepository $hostingPlatformRepository,
        UserWebsiteRepository $userWebsiteRepository,
        HostingClusterRepository $hostingClusterRepository
    ) {
        $this->hostingPlanDiscountRepository = $hostingPlanDiscountRepository;
        $this->hostingPlanPriceRepository = $hostingPlanPriceRepository;
        $this->hostingPlanRepository = $hostingPlanRepository;
        $this->hostingPlanTypeRepository = $hostingPlanTypeRepository;
        $this->hostingPlatformRepository = $hostingPlatformRepository;
        $this->userWebsiteRepository = $userWebsiteRepository;
        $this->hostingClusterRepository = $hostingClusterRepository;
    }

    /**
     * @return Collection
     */
    public function getHostingPlanTypes(): Collection
    {
        return Cache::remember('hosting_plan_types', Carbon::now()->addMinutes(1), function () {
            return $this->hostingPlanTypeRepository->getAll();
        });
    }

    /**
     * @param array $data
     * @return array
     */
    public function getHostingPlansWithLocationAndType(array $data): array
    {
        $result = $this->hostingPlanRepository->getHostingPlansWithLocationAndType($data);
        $columns = Cache::remember('hosting_plan_columns', Carbon::now()->addMinutes(1), function () {
            return $this->hostingPlanRepository->getColumnAndTypeOfTable();
        });
        $hasFree = $this->userWebsiteRepository->checkFreeWebsiteExist();

        return [
            'data' => $result,
            'columns' => $columns,
            'has_free' => $hasFree
        ];
    }

    /**
     * @param array $data
     * @return HostingPlan
     */
    public function createHostingPlans(array $data): HostingPlan
    {
        $price = $data['price'];
        $hosting_cluster_ids = $data['hosting_cluster_ids'];
        unset($data['price']);
        unset($data['hosting_cluster_ids']);

        $hostingPlan = $this->hostingPlanRepository->createHostingPlans($data);
        $dc_location_ids = $this->hostingClusterRepository->getLocationIdsByClusterIds($hosting_cluster_ids);

        // insert prices
        $hostingPlan->prices()->create([
            'month' => self::ONE_MONTH,
            'price' => $price,
            'active' => self::STATUS_ACTIVE
        ]);

        // insert dc location
        $hostingPlan->dcLocation()->sync($dc_location_ids);
        $hostingPlan->cluster()->sync($hosting_cluster_ids);

        return $hostingPlan;
    }

    /**
     * @param array $data
     * @param int $id
     * @return HostingPlan
     */
    public function updateHostingPlans(array $data, int $id): HostingPlan
    {
        $price = $data['price'];
        $hosting_cluster_ids = $data['hosting_cluster_ids'];
        unset($data['price']);
        unset($data['hosting_cluster_ids']);

        $hostingPlan = $this->hostingPlanRepository->updateHostingPlans($data, $id);
        $dc_location_ids = $this->hostingClusterRepository->getLocationIdsByClusterIds($hosting_cluster_ids);

        $hostingPlan->prices[0]->price = $price;
        $hostingPlan->push();

        $hostingPlan->dcLocation()->sync($dc_location_ids);
        $hostingPlan->cluster()->sync($hosting_cluster_ids);

        return $hostingPlan;
    }

    /**
     * @param int $id
     */
    public function deleteHostingPlans(int $id)
    {
        $this->hostingPlanRepository->destroy($id);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function listHostingPlans(): LengthAwarePaginator
    {
        $listHostingPlans = $this->hostingPlanRepository->withoutActiveScope()->getHostingPlans();

        foreach ($listHostingPlans as $key => $hostingPlan) {
            $listHostingPlans[$key]['app storage'] = $hostingPlan->hosting_app_storage;
            $listHostingPlans[$key]['database storage'] = $hostingPlan->hosting_db_storage;
        }

        return $listHostingPlans;
    }

    /**
     * @return Collection
     */
    public function listHostingPlanTypeDropdowns(): Collection
    {
        return $this->hostingPlanTypeRepository->getAll();
    }

    /**
     * @return Collection
     */
    public function listHostingPlanPlatformDropdowns(): Collection
    {
        return $this->hostingPlatformRepository->getAll();
    }

    /**
     * @param int $id
     * @return HostingPlan|null
     */
    public function getHostingPlanById(int $id): ?HostingPlan
    {
        return $this->hostingPlanRepository->getById($id);
    }
}
