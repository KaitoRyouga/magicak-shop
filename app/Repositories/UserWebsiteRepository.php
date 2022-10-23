<?php

namespace App\Repositories;

use App\Managers\UserWebsiteManager;
use App\Managers\HostingManager;
use App\Models\UserWebsite;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserWebsiteRepository extends BaseRepository
{
    /**
     * UserWebsiteRepository constructor.
     * @param UserWebsite $userWebsite
     *
     */
    public function __construct(UserWebsite $userWebsite)
    {
        $this->model = $userWebsite;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getUserWebsites(): LengthAwarePaginator
    {
        return $this->model->with([
            'user' => function ($query) {
                $query->select('id', 'keycloak_username');
            },
            'template' => function ($query) {
                $query->select('id', 'name', 'thumbnail', 'capture', 'url');
            },
            'hostingPlan' => function ($query) {
                $query->select('id', 'name');
            },
            'dcLocation' => function ($query) {
                $query->select('id', 'location');
            },
            'domainType' => function ($query) {
                $query->select('id', 'name');
            },
            'domain' => function ($query) {
                $query->select('id', 'domain_name', 'expiration_date');
            },
            'websiteMessages'
        ])
            ->where('created_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(5);
    }

    /**
     * @param int $id
     * @param bool $isGetAll
     * @return UserWebsite|null
     */
    public function getUserWebsiteById(int $id, bool $isGetAll): ?UserWebsite
    {
        $query = $this->model->with([
            'user',
            'template',
            'hostingPlan',
            'dcLocation',
            'domainType',
            'domain'
        ])
            ->where('id', $id);

        if (!$isGetAll) {
            $query->where('created_id', Auth::id());
        }

        return $query->first();
    }

    /**
     * @param array $data
     */
    public function deleteByUserIdAndUserWebsiteId(array $data): void
    {
        $delete = $this->model->where('id', $data['user_website_id'])
            ->where('created_id', $data['user_id'])
            ->where('status', UserWebsiteManager::STATUS_INITIAL);

        $delete->update(
            [
                'deleted_id' => auth()->id(),
                'status' => UserWebsiteManager::STATUS_DELETE,
                'current_tasks' => UserWebsiteManager::CURRENT_TASK_DELETE_WEBSITE,
            ]
        );
    }

    /**
     * @param array $data
     */
    public function deleteUserWebsite(array $data): void
    {
        $delete = $this->model->where('id', $data['user_website_id'])
            ->where('created_id', $data['user_id'])
            ->where('status', UserWebsiteManager::STATUS_ERROR);

        $delete->update(
            [
                'deleted_id' => auth()->id(),
                'status' => UserWebsiteManager::STATUS_DELETE,
                'current_tasks' => UserWebsiteManager::CURRENT_TASK_DELETE_WEBSITE,
            ]
        );
        
        $delete->delete();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function updateCurrentTaskUserWebsite(array $data): bool
    {
        $delete = $this->model->where('id', $data['user_website_id'])
            ->where('created_id', $data['created_id']);

        return $delete->update(
            [
                'current_tasks' => $data['current_tasks'],
            ]
        );
    }

    /**
     * @param array $data
     * @return bool
     */
    public function deleteUserWebsiteWithUpdatingDomain(array $data): bool
    {
        $delete = $this->model->where('id', $data['user_website_id'])
            ->where('created_id', $data['created_id']);

        return $delete->update(
            [
                'current_tasks' => UserWebsiteManager::CURRENT_TASK_DELETE_WEBSITE,
                'status' => UserWebsiteManager::STATUS_DELETE,
                'deleted_id' => auth()->id(),
                'deleted_at' => now()
            ]
        );

    }

    /**
     * @param array $data
     * @return mixed
     */
    public function checkBusinessNameExist(array $data): ?UserWebsite
    {
        return $this->model->where('business_name', $data['business_name'])->where('created_id', Auth::id())->first();
    }

    /**
     * @return bool
     */
    public function checkFreeWebsiteExist(): bool
    {
        $oldUserWebsite = $this->model->where(function ($query) {
                $query->where('hosting_plan_id', HostingManager::HOSTING_PLAN_WEB_FREE)
                ->orWhere('hosting_plan_id', HostingManager::HOSTING_PLAN_BUSINESS_FREE);
            })
            ->where('created_id', Auth::id())
            ->first();

        if ($oldUserWebsite) {
            return true;
        }

        return false;
    }

    /**
     * @return Collection
     */
    public function getUserWebsiteUpdatingDomain(): Collection
    {
        return $this->model->where('status', UserWebsiteManager::STATUS_UPDATING_DOMAIN_IN_WEBSITE)->get();
    }
}
