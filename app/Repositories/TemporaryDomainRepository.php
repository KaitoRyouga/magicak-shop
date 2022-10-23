<?php

namespace App\Repositories;

use App\Managers\DomainManager;
use App\Models\SystemDomain;
use App\Models\TemporaryDomain;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Services\DreamScapeService;
use App\Services\DreamScapeServiceProduction;
use App\Services\GodaddyService;
use App\Services\GodaddyServiceProduction;
class TemporaryDomainRepository extends BaseRepository
{
    /**
     * @var DreamScapeService
     */
    protected $dreamScapeService;

    /**
     * @var DreamScapeServiceProduction
     */
    protected $dreamScapeServiceProduction;

    /**
     * @var GodaddyService
     */
    protected $godaddyService;

    /**
     * @var GodaddyServiceProduction
     */
    protected $godaddyServiceProduction;

    /**
     * TemporaryDomainRepository constructor.
     * @param TemporaryDomain $temporaryDomain
     * @param DreamScapeService $dreamScapeService
     * @param DreamScapeServiceProduction $dreamScapeServiceProduction
     */
    public function __construct(
        TemporaryDomain $temporaryDomain,
        DreamScapeService $dreamScapeService,
        DreamScapeServiceProduction $dreamScapeServiceProduction,
        GodaddyService $godaddyService,
        GodaddyServiceProduction $godaddyServiceProduction
    )
    {
        $this->model = $temporaryDomain;
        $this->dreamScapeService = $dreamScapeService;
        $this->dreamScapeServiceProduction = $dreamScapeServiceProduction;
        $this->godaddyService = $godaddyService;
        $this->godaddyServiceProduction = $godaddyServiceProduction;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getAllTemporaryDomain(): LengthAwarePaginator
    {
        return $this->model->paginate(10);
    }

    /**
     * @param array $data
     * @return LengthAwarePaginator
     */
    public function getAllTemporaryDomainWithUserWebsite(array $data): LengthAwarePaginator
    {
        return $this->model->with([
            'userWebsite'
        ])
        ->where('domain_name', 'like', '%' . $data['domain_name'])
        ->paginate(10);
    }

    /**
     * @param array $data
     * @return TemporaryDomain|null
     */
    public function getTemporaryDomainAvailable(array $data): ?TemporaryDomain
    {
        return $this->model
            ->where('available', 1)
            ->where('dc_location_id', $data['dc_location_id'])
            ->first();
    }

    /**
     * @param string $temporaryDomainName
     * @return TemporaryDomain|null
     */
    public function getTemporaryDomainByName(string $temporaryDomainName): ?TemporaryDomain
    {
        return $this->model
            ->where('TemporaryDomain_name', $temporaryDomainName)
            ->first();
    }

    /**
     * @param int $id
     * @param array $data
     * @return TemporaryDomain
     */
    public function changeAvailable(int $id, array $data): TemporaryDomain
    {
        $temporaryDomain = $this->getById($id);
        $temporaryDomain->available = $data['available'];
        $temporaryDomain->save();

        return $temporaryDomain;
    }

    /**
     * @param int $id
     * @param array $data
     * @return TemporaryDomain
     */
    public function updateOrCreateTemporaryDomain(?int $id = null, array $data): TemporaryDomain
    {
        return $this->updateOrCreate($id, $data);
    }

    /**
     * @param string $domainName
     * @return TemporaryDomain|null
     */
    public function getDomainByName(string $domainName): ?TemporaryDomain
    {
        return $this->model
            ->where('domain_name', $domainName)
            ->first();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getTemporaryDomainWithSystemDomain(array $data)

    {
        return $this->model
            ->where('domain_name', 'like', '%' . $data['domain_name'])
            ->get();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getTemporaryDomainNotAvailableWithSystemDomain(array $data)

    {
        return $this->model
            ->where('domain_name', 'like', '%' . $data['domain_name'])
            ->where('available', 0)
            ->get();
    }

    /**
     * @param array $data
     * @return LengthAwarePaginator
     */
    public function getTemporaryDomainPaginate(array $data): LengthAwarePaginator
    {
        return $this->model
            ->where('domain_name', 'like', '%' . $data['domain_name'])
            ->paginate(10);
    }

    /**
     * @param SystemDomain $systemDomain
     * @param array $data
     * @param string $provider
     * @return array
     */
    public function updateSystemdomainWithIP(SystemDomain $systemDomain, array $data, string $provider): array
    {
        try {

            $systemDomain->ip = $data['ip'];
            $systemDomain->save();

            if ($provider == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX) {

                // TODO: fix if producion, this code is for sandbox
                if ( $systemDomain->domain_name == config('dreamScape.temp_domain_test') ) {
                    $listDNS = $this->dreamScapeServiceProduction->request("domains/$systemDomain->remote_domain_id/dns", 'GET', [], []);
                    foreach ($listDNS as $value) {
                        if(strtolower($value['type']) == DomainManager::DOMAIN_TYPE_A && ( strtolower($value['subdomain']) == "" || strtolower($value['subdomain']) == DomainManager::SUB_DOMAIN_ROOT ) ) {
                            $dataRecord = [
                                "subdomain" => $value['subdomain'],
                                "type" => $value['type'],
                                "content" => $data['ip']
                            ];

                            $record_id = $value['id'];
                            $remote_domain_id = $systemDomain->remote_domain_id;

                            $this->dreamScapeServiceProduction->request("domains/$remote_domain_id/dns/$record_id", 'PATCH', [], $dataRecord);
                        }
                    }
                } else {
                    $listDNS = $this->dreamScapeService->request("domains/$systemDomain->remote_domain_id/dns", 'GET', [], []);
                    foreach ($listDNS as $value) {
                        if(strtolower($value['type']) == DomainManager::DOMAIN_TYPE_A && ( strtolower($value['subdomain']) == "" || strtolower($value['subdomain']) == DomainManager::SUB_DOMAIN_ROOT ) ) {
                            $dataRecord = [
                                "subdomain" => $value['subdomain'],
                                "type" => $value['type'],
                                "content" => $data['ip']
                            ];

                            $record_id = $value['id'];
                            $remote_domain_id = $systemDomain->remote_domain_id;

                            $this->dreamScapeService->request("domains/$remote_domain_id/dns/$record_id", 'PATCH', [], $dataRecord);
                        }
                    }
                }
            } else if ($provider == DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION) {
                $listDNS = $this->dreamScapeServiceProduction->request("domains/$systemDomain->remote_domain_id/dns", 'GET', [], []);
                foreach ($listDNS as $value) {
                    if(strtolower($value['type']) == DomainManager::DOMAIN_TYPE_A && ( strtolower($value['subdomain']) == "" || strtolower($value['subdomain']) == DomainManager::SUB_DOMAIN_ROOT ) ) {
                        $dataRecord = [
                            "subdomain" => $value['subdomain'],
                            "type" => $value['type'],
                            "content" => $data['ip']
                        ];

                        $record_id = $value['id'];
                        $remote_domain_id = $systemDomain->remote_domain_id;

                        $this->dreamScapeServiceProduction->request("domains/$remote_domain_id/dns/$record_id", 'PATCH', [], $dataRecord);
                    }
                }
            } else if ($provider == DomainManager::PROVIDER_NAME_GODADDY_SANDBOX) {

                $dataDNS = [
                    "data" => $data['ip'],
                    "name" => DomainManager::SUB_DOMAIN_ROOT,
                    "ttl" => 600,
                    "type" => "A",
                    "weight" => 0
                ];

                if ($systemDomain->domain_name == config('godaddy.temp_domain_test')) {
                    $this->godaddyServiceProduction->updateRecordDNS($systemDomain->domain_name, DomainManager::SUB_DOMAIN_ROOT, $dataDNS);
                } else {
                    $this->godaddyService->updateRecordDNS($systemDomain->domain_name, DomainManager::SUB_DOMAIN_ROOT, $dataDNS);
                }
            } else if ($provider == DomainManager::PROVIDER_NAME_GODADDY_PRODUCTION) {

                $dataDNS = [
                    "data" => $data['ip'],
                    "name" => DomainManager::SUB_DOMAIN_ROOT,
                    "ttl" => 600,
                    "type" => "A",
                    "weight" => 0
                ];

                $this->godaddyServiceProduction->updateRecordDNS($systemDomain->domain_name, DomainManager::SUB_DOMAIN_ROOT, $dataDNS);
            }

            return [
                'status' => true,
                'message' => 'Update IP success'
            ];

        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }


    /**
     * @param TemporaryDomain $temporaryDomain
     * @param array $data
     * @param string $provider
     * @param SystemDomain $systemDomain
     * @return array
     */
    public function updateTemporaryDomainWithIP(TemporaryDomain $temporaryDomain, array $data, string $provider, SystemDomain $systemDomain): array
    {
        try {

            $temporaryDomain->ip = $data['ip'];
            $temporaryDomain->save();

            $subDomain = explode('.', $temporaryDomain->domain_name)[0];

            if ($provider == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX) {

                $dataRecord = [
                    "subdomain" => $subDomain,
                    "type" => DomainManager::DOMAIN_TYPE_A,
                    "content" => $data['ip']
                ];

                $remote_domain_id = $systemDomain->remote_domain_id;
                $record_id = $temporaryDomain->remote_domain_id;

                if ( $systemDomain->domain_name == config('dreamScape.temp_domain_test') ) {
                    $this->dreamScapeServiceProduction->request("domains/$remote_domain_id/dns/$record_id", 'PATCH', [], $dataRecord);
                } else {
                    $this->dreamScapeService->request("domains/$remote_domain_id/dns/$record_id", 'PATCH', [], $dataRecord);
                }

            } else if ($provider == DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION) {

                $dataRecord = [
                    "subdomain" => $subDomain,
                    "type" => DomainManager::DOMAIN_TYPE_A,
                    "content" => $data['ip']
                ];

                $remote_domain_id = $systemDomain->remote_domain_id;
                $record_id = $temporaryDomain->remote_domain_id;

                $this->dreamScapeServiceProduction->request("domains/$remote_domain_id/dns/$record_id", 'PATCH', [], $dataRecord);
            } else if ($provider == DomainManager::PROVIDER_NAME_GODADDY_SANDBOX) {

                $dataDNS = [
                    "data" => $data['ip'],
                    "name" => $subDomain,
                    "ttl" => 600,
                    "type" => "A",
                    "weight" => 0
                ];

                if ($systemDomain->domain_name == config('godaddy.temp_domain_test')) {
                    $this->godaddyServiceProduction->updateRecordDNS($systemDomain->domain_name, $subDomain, $dataDNS);
                } else {
                    $this->godaddyService->updateRecordDNS($systemDomain->domain_name, $subDomain, $dataDNS);
                }
            } else if ($provider == DomainManager::PROVIDER_NAME_GODADDY_PRODUCTION) {

                $dataDNS = [
                    "data" => $data['ip'],
                    "name" => $subDomain,
                    "ttl" => 600,
                    "type" => "A",
                    "weight" => 0
                ];

                $this->godaddyServiceProduction->updateRecordDNS($systemDomain->domain_name, $subDomain, $dataDNS);
            }

            return [
                'status' => true,
                'message' => 'Update IP success'
            ];

        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * @param int $count
     * @param array $data
     * @param string $provider
     * @param SystemDomain $systemDomain
     * @param int $dc_location_id
     * @return array
     */
    public function addMoreTemporaryDomainWithIP(int $count, array $data, string $provider, SystemDomain $systemDomain, int $dc_location_id): array
    {
        try {
            $dataAdd = [
                'remote_domain_id' => null,
                'customer_id' => $systemDomain->customer_id,
                'domain_name' => 'temp' . $count . '.' . $systemDomain->domain_name,
                'ip' => $data['ip'],
                'domain_type_id' => DomainManager::SUB_DOMAIN,
                'created_id' => auth()->id(),
                'domain_register_from' => explode('_', $provider)[0],
                'available' => 1,
                'status' => DomainManager::STATUS_DOMAIN_NOT_IN_USED,
                'dc_location_id' => $dc_location_id,
                'active' => 1
            ];

            $temporaryDomain = $this->updateOrCreateTemporaryDomain(null, $dataAdd);

            if ($provider == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX) {

                $dataRecord = [
                    "subdomain" =>'temp' . $count,
                    "type" => DomainManager::DOMAIN_TYPE_A,
                    "content" => $data['ip']
                ];

                $remote_domain_id = $systemDomain->remote_domain_id;

                if ( $systemDomain->domain_name == config('dreamScape.temp_domain_test') ) {
                    $record = $this->dreamScapeServiceProduction->request("domains/$remote_domain_id/dns", 'POST', [], $dataRecord);
                } else {
                    $record = $this->dreamScapeService->request("domains/$remote_domain_id/dns", 'POST', [], $dataRecord);
                }

                $this->updateOrCreateTemporaryDomain($temporaryDomain->id, ['remote_domain_id' => $record['id']]);
            } else if ($provider == DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION) {

                $dataRecord = [
                    "subdomain" => 'temp' . $count,
                    "type" => DomainManager::DOMAIN_TYPE_A,
                    "content" => $data['ip']
                ];

                $remote_domain_id = $systemDomain->remote_domain_id;

                $record = $this->dreamScapeServiceProduction->request("domains/$remote_domain_id/dns", 'POST', [], $dataRecord);

                $this->updateOrCreateTemporaryDomain($temporaryDomain->id, ['remote_domain_id' => $record['id']]);
            } else if ($provider == DomainManager::PROVIDER_NAME_GODADDY_SANDBOX) {

                $dataDNS = [
                    "data" => $data['ip'],
                    "name" => 'temp' . $count,
                    "ttl" => 600,
                    "type" => "A",
                    "weight" => 0
                ];

                $record = [];

                if ($systemDomain->domain_name == config('godaddy.temp_domain_test')) {
                    $record = $this->godaddyServiceProduction->createNewRecordDNS($systemDomain->domain_name, $dataDNS);
                } else {
                    $record = $this->godaddyService->createNewRecordDNS($systemDomain->domain_name, $dataDNS);
                }

                $this->updateOrCreateTemporaryDomain($temporaryDomain->id, ['remote_domain_id' => $record['id']]);
            } else if ($provider == DomainManager::PROVIDER_NAME_GODADDY_PRODUCTION) {

                $dataDNS = [
                    "data" => $data['ip'],
                    "name" => 'temp' . $count,
                    "ttl" => 600,
                    "type" => "A",
                    "weight" => 0
                ];

                $record = $this->godaddyServiceProduction->createNewRecordDNS($systemDomain->domain_name, $dataDNS);

                $this->updateOrCreateTemporaryDomain($temporaryDomain->id, ['remote_domain_id' => $record['id']]);
            }

            return [
                'status' => true,
                'message' => 'Add more temporary domain success'
            ];

        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }
}
