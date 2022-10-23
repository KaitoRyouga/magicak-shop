<?php

namespace App\Repositories;

use App\Managers\DomainManager;
use App\Models\SystemDomain;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SystemDomainRepository extends BaseRepository
{
    /**
     * SystemDomainRepository constructor.
     * @param SystemDomain $systemDomain
     */
    public function __construct(SystemDomain $systemDomain)
    {
        $this->model = $systemDomain;
    }

    /**
     * @param array $data
     * @return SystemDomain|null
     */
    public function checkSystemDomainExist(array $data): ?SystemDomain
    {
        return $this->model
            ->where('domain_name', $data['domain_name'])
            ->first();
    }

    /**
     * @param array $data
     * @param int $id
     * @return SystemDomain
     */
    public function updateOrCreateSystemDomain(?int $id = null, array $data): SystemDomain
    {
        return $this->updateOrCreate($id, $data);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getSystemDomains(): LengthAwarePaginator
    {
        return $this->model->paginate();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getSystemDomainByManageTemporaryDomain(): LengthAwarePaginator
    {
        return $this->model->select('id', 'domain_name', 'ip')->where('domain_type_id', DomainManager::NEW_DOMAIN)->paginate(10);
    }

    /**
     * @param int $id
     * @return SystemDomain|null
     */
    public function getSystemDomainByRemoteId(int $id): ?SystemDomain
    {
        return $this->model
            ->where('remote_domain_id', $id)
            ->first();
    }

}
