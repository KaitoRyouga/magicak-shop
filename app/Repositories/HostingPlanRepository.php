<?php

namespace App\Repositories;

use App\Models\HostingPlan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HostingPlanRepository extends BaseRepository
{
    /**
     * HostingPlanRepository constructor.
     * @param HostingPlan $hostingPlan
     */
    public function __construct(HostingPlan $hostingPlan)
    {
        $this->model = $hostingPlan;
    }

    /**
     * @param array $data
     * @return Collection
     */
    public function getHostingPlansWithLocationAndType(array $data): Collection
    {
        return Cache::remember('hosting_plans_with_location_id_' . $data['location_id'] . 'and_hosting_type_id_' . $data['hosting_type_id'], Carbon::now()->addMinutes(1), function () use ($data) {
            return $this->model
                ->with([
                    'type' => function ($query) use ($data) {
                        $query->select('id', 'name')->where('id', $data['hosting_type_id']);
                    },
                    'cluster' => function ($query) use ($data) {
                        $query->with(['systemDomain', 'platform'])->where('dc_location_id', $data['location_id']);
                    },
                    'prices',
                    'dcLocation' => function ($query) use ($data) {
                        $query->select('id', 'location')->where('id', $data['location_id']);
                    }
                ])
                ->whereHas('type', function ($query) use ($data) {
                    $query->where('id', $data['hosting_type_id']);
                })
                ->get();
        });
    }

    /**
     * @param array $data
     * @return HostingPlan
     */
    public function createHostingPlans(array $data): HostingPlan
    {
        return $this->updateOrCreate(null, $data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return HostingPlan
     */
    public function updateHostingPlans(array $data, int $id): HostingPlan
    {
        return $this->updateOrCreate($id, $data);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getHostingPlans(): LengthAwarePaginator
    {
        return Cache::remember('hosting_plans', Carbon::now()->addMinutes(1), function () {
            return $this->model
                ->with([
                    'type' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'cluster' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'dcLocation' => function ($query) {
                        $query->select('id', 'location', 'code');
                    },
                    'prices'
                ])
                ->paginate();
        });
    }
}
