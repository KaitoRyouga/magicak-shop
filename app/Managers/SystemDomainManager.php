<?php

namespace App\Managers;

use App\Repositories\SystemDomainRepository;
use App\Services\DreamScapeService;
use App\Services\DreamScapeServiceProduction;
use App\Models\SystemDomain;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SystemDomainManager extends BaseManager
{
    const NEW_DOMAIN = 1;
    const SUB_DOMAIN = 2;
    const OWN_DOMAIN = 3;

    const DOMAIN_REGISTER_FROM_VODIEN = 'Vodien';

    const DOMAIN_TYPE_A = 'A';
    const DOMAIN_TYPE_CNAME = 'CNAME';
    const SUB_DOMAIN_ROOT = '@';
    const SUB_DOMAIN_WWW = 'www';

    /**
     * @var SystemDomainRepository
     */
    protected $systemDomainRepository;

    /**
     * @var DreamScapeService
     */
    protected $dreamScapeService;

    /**
     * @var DreamScapeServiceProduction
     */
    protected $dreamScapeServiceProduction;

    /**
     * SystemDomainManager constructor.
     * @param DreamScapeService $dreamScapeService
     * @param SystemDomainRepository $systemDomainRepository
     * @param DreamScapeServiceProduction $dreamScapeServiceProduction
     *
     */
    public function __construct(
        DreamScapeService $dreamScapeService,
        SystemDomainRepository $systemDomainRepository,
        DreamScapeServiceProduction $dreamScapeServiceProduction
    ) {
        $this->dreamScapeService = $dreamScapeService;
        $this->systemDomainRepository = $systemDomainRepository;
        $this->dreamScapeServiceProduction = $dreamScapeServiceProduction;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function checkSystemDomainExist(array $data)
    {
        if ($data['domain_register_from'] = self::DOMAIN_REGISTER_FROM_VODIEN) {
            if ($data['domain_type_id'] == self::NEW_DOMAIN) {
                $res = $this->dreamScapeService->request('domains/availability', 'GET', [
                    "domain_names" => [$data['domain_name']],
                    "currency" => DomainManager::DEFAULT_CURRENCY
                ]);

                if (isset($res[0]['checking_error'])) {
                    return [
                        'data' => null,
                        'message' => $res[0]['checking_error'],
                        'code' => 400
                    ];
                }

                if ($res[0]['is_available']) {
                    return [
                        'data' => null,
                        'message' => 'Domain name is available',
                        'code' => 200
                    ];
                } else {
                    return [
                        'data' => null,
                        'message' => 'Domain name is not available',
                        'code' => 400
                    ];
                }
            } else if ($data['domain_type_id'] == self::SUB_DOMAIN) {

                $systemDomain = explode('.', $data['domain_name'])[1] . '.' . explode('.', $data['domain_name'])[2];

                $domain = $this->systemDomainRepository->checkSystemDomainExist([
                    'domain_name' => $systemDomain
                ]);

                $listDNS = $this->dreamScapeService->request('domains/' . $domain->remote_domain_id . '/dns', 'GET');

                foreach ($listDNS as $value) {
                    if ($value['subdomain'] == explode('.', $data['domain_name'])[0]) {
                        return [
                            'data' => null,
                            'message' => trans('Subdomain already exist'),
                            'code' => 400
                        ];
                    }
                }

                return [
                    'data' => null,
                    'message' => null,
                    'code' => 200
                ];
            }
        }
    }

    /**
     * @param array $data
     * @return SystemDomain
     */
    public function updateOrCreateSystemDomain(array $data): SystemDomain
    {
        // check system domain existed
        $systemDomain = $this->systemDomainRepository->checkSystemDomainExist($data);

        $systemDomainId = empty($systemDomain) ? null : $systemDomain->id;

        if ($systemDomainId) {
            $dataCreate = [
                'domain_name' => $systemDomain->domain_name,
                'domain_auth_key' => $systemDomain->domain_auth_key,
                'domain_type_id' => $systemDomain->domain_type_id,
                'ip' => $data['ip'] ?? null,
                'active' => self::STATUS_ACTIVE,
                'created_id' => $systemDomain->created_id
            ];
        } else {
            $dataCreate = [
                'domain_name' => $data['domain_name'],
                'domain_auth_key' => $data['domain_auth_key'] ?? null,
                'domain_type_id' => $data['domain_type_id'],
                'ip' => $data['ip'] ?? null,
                'active' => self::STATUS_ACTIVE,
                'created_id' => Auth::id()
            ];
        }

        $newDomain = $this->systemDomainRepository->updateOrCreateSystemDomain($systemDomainId ?? null, $dataCreate);

        if ($data['domain_type_id'] == self::NEW_DOMAIN) {

            $this->registerSystemDomain($newDomain, $data['domain_type_id']);

        } elseif ($data['domain_type_id'] == self::SUB_DOMAIN) {

            $rawDomain = explode('.', $data['domain_name'])[1] . '.' . explode('.', $data['domain_name'])[2];
            $rawDomain = $this->systemDomainRepository->checkSystemDomainExist(['domain_name' => $rawDomain]);
            $this->registerSystemDomain($rawDomain, $data['domain_type_id'], $newDomain);

        }

        return $newDomain;
    }

    /**
     * @param SystemDomain $systemDomain
     * @param SystemDomain $subDomain
     * @param int $domainTypeId
     * @return SystemDomain
     * @throws \Exception
     */
    public function registerSystemDomain(SystemDomain $systemDomain, int $domainTypeId, $subDomain = null): SystemDomain
    {
        // temp => delete later
        $domainName = $systemDomain->domain_name;
        $customerId = ( strtolower(config('app.env')) === 'production' && $domainName == config('dreamScape.temp_domain_test') ) ? config('dreamScape.customer_id_production') : config('dreamScape.customer_id_sandbox');

        // set full domain name
        $systemDomainName = $systemDomain->domain_name;
        $systemDomainIp = $systemDomain ? $systemDomain->ip : config('magicak.ip');

        $dataDomain = [
            "domain_name" => $systemDomainName,
            "customer_id" => $customerId,
            "period" => DomainManager::DEFAULT_PERIOD_MAGICAK_DOMAIN,
            "eligibility_data" => [
                [
                    "name" => "business_type",
                    "value" => "Other"
                ],
                [
                    "name" => "business_name",
                    "value" => "magicak"
                ],
                [
                    "name" => "business_number_type",
                    "value" => "VIC BN"
                ],
                [
                    "name" => "business_number",
                    "value" => "201904562M"
                ]
            ]
        ];

        if ($domainTypeId === self::NEW_DOMAIN) {
            try {
                $domainNew = $this->dreamScapeService->request('domains', 'POST', [], $dataDomain);

                $dataCreate = [
                    'remote_domain_id' => $domainNew["id"],
                    'domain_auth_key' => $domainNew['auth_key'],
                    'customer_id' => $customerId,
                    'domain_name' => $systemDomainName,
                    'registration_date' => Carbon::createFromTimeString($domainNew['start_date']),
                    'expiration_date' => Carbon::createFromTimeString($domainNew['expiry_date']),
                    'ip' => $systemDomainIp,
                ];

                $this->signPointTo($domainNew["id"], $systemDomainIp, $systemDomainIp);

                $newDomain = $this->systemDomainRepository->updateOrCreateSystemDomain($systemDomain->id, $dataCreate);

            } catch (\Throwable $e) {
                response()->json([
                    'code' => 500,
                    'response' => [],
                    'message' => $e->getMessage()
                ])->send();
                exit;
            }
        } elseif ($domainTypeId === self::SUB_DOMAIN) {
            $dataDNS = [
                "type" => self::DOMAIN_TYPE_A,
                "content" => $subDomain ? $subDomain->ip : config('magicak.ip'),
                "subdomain" => explode('.', $subDomain->domain_name)[0],
            ];

            try {
                // temp => fix later
                if ( strtolower(config('app.env')) === 'production' && $domainName == config('dreamScape.temp_domain_test') ) {
                    $record = $this->dreamScapeService->request("domains/" . $systemDomain->remote_domain_id . "/dns", 'POST', [], $dataDNS);
                } else {

                }
            } catch (\Throwable $e) {
                response()->json([
                    'code' => 500,
                    'response' => [],
                    'message' => $e->getMessage()
                ])->send();
                exit;
            }

            $dataCreate = [
                'remote_domain_id' => $record['id'],
                'customer_id' => $customerId,
                'domain_name' => $subDomain->domain_name,
                'ip' => $subDomain ? $subDomain->ip : config('magicak.ip'),
                'registration_date' => $systemDomain->registration_date,
                'expiration_date' => $systemDomain->expiration_date
            ];

            $newDomain = $this->systemDomainRepository->updateOrCreateSystemDomain($subDomain->id, $dataCreate);
        }

        return $newDomain;
    }

    /**
     * @param int $remote_domain_id
     * @param string $ip
     */
    public function signPointTo(int $remote_domain_id, string $ip = null): void
    {
        $check = $this->dreamScapeService->request("domains/" . $remote_domain_id . '/dns', 'GET', [], []);

        foreach ($check as $value) {
            $this->dreamScapeService->request("domains/$remote_domain_id/dns/" . $value['id'], 'DELETE', [], []);
        }

        $dataDNSRoot = [
            "type" => self::DOMAIN_TYPE_A,
            "content" => $ip,
            "subdomain" => self::SUB_DOMAIN_ROOT,
        ];

        $dataDNSWWW = [
            "type" => self::DOMAIN_TYPE_CNAME,
            "content" => self::SUB_DOMAIN_ROOT,
            "subdomain" => self::SUB_DOMAIN_WWW,
        ];

        $this->dreamScapeService->request("domains/" . $remote_domain_id . "/dns", 'POST', [], $dataDNSRoot);
        $this->dreamScapeService->request("domains/" . $remote_domain_id . "/dns", 'POST', [], $dataDNSWWW);
    }

    /**
     * @param array $data
     */
    public function updateDNS(array $data)
    {
        $listDNS = $this->dreamScapeService->request("domains/" . $data['id'] . "/dns", 'GET', [], []);

        $recordRoot = false;

        foreach ($listDNS as $value) {
            if ($value['subdomain'] === $data['subdomain']) {
                $recordRoot = true;
                $dataRoot = [
                    "type" => self::DOMAIN_TYPE_A,
                    "content" => $data['ip'],
                    "subdomain" => $data['subdomain']
                ];
                $this->dreamScapeService->request("domains/" . $data['id'] . "/dns/" . $value['id'], 'PATCH', [], $dataRoot);
            }
        }

        if (!$recordRoot) {

            $dataDNSRoot = [
                "type" => self::DOMAIN_TYPE_A,
                "content" => $data['ip'],
                "subdomain" => $data['subdomain']
            ];
            $this->dreamScapeService->request("domains/" . $data['id'] . "/dns", 'POST', [], $dataDNSRoot);
        }
    }

    /**
     * @param array $data
     */
    public function configureIpSystemDomain(array $data): void
    {

        if ($data['domain_type_id'] == self::NEW_DOMAIN) {

            $systemDomain = $this->systemDomainRepository->checkSystemDomainExist($data);

            $domainDomain = [
                'id' => $systemDomain->remote_domain_id,
                'subdomain' => self::SUB_DOMAIN_ROOT,
                'ip' => $data['ip']
            ];

        } elseif ($data['domain_type_id'] == self::SUB_DOMAIN) {

            $domainName = explode('.', $data['domain_name'])[1] . '.' . explode('.', $data['domain_name'])[2];
            $systemDomain = $this->systemDomainRepository->checkSystemDomainExist([
                'domain_name' => $domainName
            ]);

            $domainDomain = [
                'id' => $systemDomain->remote_domain_id,
                'subdomain' => explode('.', $data['domain_name'])[0],
                'ip' => $data['ip']
            ];

        }

        $this->updateDNS($domainDomain);
    }

    /**
     * @param array $data
     * @return SystemDomain
     */
    public function createSystemDomain(array $data): SystemDomain
    {
        return $this->updateOrCreateSystemDomain($data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return SystemDomain
     */
    public function updateSystemDomain(array $data, int $id): SystemDomain
    {
        $this->configureIpSystemDomain($data);
        return $this->systemDomainRepository->updateOrCreateSystemDomain($id, $data);
    }

    /**
     * @param int $id
     */
    public function deleteSystemDomain(int $id)
    {
        $systemDomain = $this->systemDomainRepository->getById($id);
        $rawDomain = explode('.', $systemDomain->domain_name)[1] . '.' . explode('.', $systemDomain->domain_name)[2];
        $domain = $this->systemDomainRepository->checkSystemDomainExist([
            'domain_name' => $rawDomain
        ]);
        $this->dreamScapeService->request("domains/$domain->remote_domain_id/dns/$systemDomain->remote_domain_id", 'DELETE', [], []);
        $this->systemDomainRepository->destroy($id);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function listSystemDomains(): LengthAwarePaginator
    {
        return $this->systemDomainRepository->withoutActiveScope()->getSystemDomains();
    }

    /**
     * @return Collection
     */
    public function listSystemDomainDropdowns(): Collection
    {
        return $this->systemDomainRepository->getAll();
    }

    /**
     * @param int $id
     * @return SystemDomain|null
     */
    public function getSystemDomainByRemoteId(int $id): ?SystemDomain
    {
        return $this->systemDomainRepository->getSystemDomainByRemoteId($id);
    }

    /**
     * @param array $data
     * @return SystemDomain|null
     */
    public function getSystemDomainByName(array $data): ?SystemDomain
    {
        return $this->systemDomainRepository->checkSystemDomainExist($data);
    }
}
