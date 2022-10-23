<?php

namespace App\Repositories;

use App\Managers\SystemDomainManager;
use App\Managers\DomainManager;
use App\Models\Domain;
use App\Models\SystemDomain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Services\DreamScapeService;
use App\Services\DreamScapeServiceProduction;
use App\Services\GodaddyService;
use App\Services\GodaddyServiceProduction;
use App\Repositories\SettingRepository;
use Carbon\Carbon;

class DomainRepository extends BaseRepository
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
     * @var SettingRepository
     */
    protected $settingRepository;

    /**
     * @var GodaddyService
     */
    protected $godaddyService;

    /**
     * @var GodaddyServiceProduction
     */
    protected $godaddyServiceProduction;

    /**
     * @var SystemDomainManager
     */
    protected $systemDomainManager;

    /**
     * DomainRepository constructor.
     * @param Domain $domain
     * @param DreamScapeService $dreamScapeService
     * @param DreamScapeServiceProduction $dreamScapeServiceProduction
     * @param SettingRepository $settingRepository
     * @param GodaddyService $godaddyService
     * @param SystemDomainManager $systemDomainManager
     * @param GodaddyServiceProduction $godaddyServiceProduction
     */
    public function __construct(
        Domain $domain,
        DreamScapeService $dreamScapeService,
        DreamScapeServiceProduction $dreamScapeServiceProduction,
        SettingRepository $settingRepository,
        GodaddyService $godaddyService,
        SystemDomainManager $systemDomainManager,
        GodaddyServiceProduction $godaddyServiceProduction
    )
    {
        $this->model = $domain;
        $this->dreamScapeService = $dreamScapeService;
        $this->dreamScapeServiceProduction = $dreamScapeServiceProduction;
        $this->settingRepository = $settingRepository;
        $this->godaddyService = $godaddyService;
        $this->godaddyServiceProduction = $godaddyServiceProduction;
        $this->systemDomainManager = $systemDomainManager;
    }

    /**
     * @param array $data
     * @return Domain|null
     */
    public function checkDomainNameExist(array $data): ?Domain
    {
        return $this->model
            ->where('domain_name', $data['domain_name'])
            ->where('domain_type_id', $data['domain_type_id'])
            ->first();
    }

    /**
     * @param string $domainName
     * @return Domain|null
     */
    public function getDomainByName(string $domainName): ?Domain
    {
        return $this->model
            ->where('domain_name', $domainName)
            ->where('created_id', Auth::id())
            ->first();
    }

    /**
     * @param bool $isGetAll
     * @return LengthAwarePaginator
     */
    public function getDomains(bool $isGetAll): LengthAwarePaginator
    {
        $query = $this->model
                ->with([
                    'userWebsite'
                ])
                ->orderBy('created_at', 'desc');
        if (!$isGetAll) {
            $query->where('created_id', Auth::id());
        }

        return $query->paginate(10);
    }

    /**
     * @param array $data
     * @return Domain|null
     */
    public function getDomainById(array $data): ?Domain
    {
        return $this->model
            ->where('id', $data['id'])
            ->where('created_id', Auth::id())
            ->first();
    }

    /**
     * @param int $id
     * @param array $data
     */
    public function destroyDomain(int $id, array $data): void
    {
        $this->updateOrCreate($id, $data);
        $this->model->destroy($id);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Domain
     */
    public function updateOrCreateDomain(?int $id = null, array $data): Domain
    {
        return $this->updateOrCreate($id, $data);
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getType($type)
    {
        if (!isset($type) || empty($type)) {
            $type = $this->settingRepository->getProviderDomain();
            $environment = strtolower(config('app.env')) === 'production' ? '_production' : '_sandbox';
            $type = $type . $environment;
        }

        return $type;
    }

    /**
     * @param int $remote_domain_id
     * @param SystemDomain $systemDomain
     * @param string $currentType
     */
    public function signPointToDreamScape(int $remote_domain_id, SystemDomain $systemDomain = null, string $currentType = null): void
    {
        $check = $this->getRecordDNS($remote_domain_id, $currentType);

        $type = $this->getType($currentType);

        foreach ($check as $value) {

            $record_id = $value['id'];

            if ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX) {

                $this->dreamScapeService->request("domains/$remote_domain_id/dns/$record_id", 'DELETE');

            } elseif ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION) {

                $this->dreamScapeServiceProduction->request("domains/$remote_domain_id/dns/$record_id", 'DELETE');
            }
        }

        $dataDNSRoot = [
            "type" => DomainManager::DOMAIN_TYPE_A,
            "content" => $systemDomain ? $systemDomain->ip : config('magicak.ip'),
            "subdomain" => DomainManager::SUB_DOMAIN_ROOT,
        ];

        $dataDNSWWW = [
            "type" => DomainManager::DOMAIN_TYPE_CNAME,
            "content" => DomainManager::SUB_DOMAIN_ROOT,
            "subdomain" => DomainManager::SUB_DOMAIN_WWW,
        ];

        $this->createNewRecordDNS($remote_domain_id, $dataDNSRoot, $type);
        $this->createNewRecordDNS($remote_domain_id, $dataDNSWWW, $type);
    }

    /**
     * @param array $data
     * @param string $type
     * @return array
     */
    public function checkAvailableListDomain(array $data, string $type = null): array
    {
        try {

            $type = $this->getType($type);

            if ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX) {

                $res = $this->dreamScapeService->request('domains/availability', 'GET', [
                    "domain_names" => $data,
                    "currency" => DomainManager::DEFAULT_CURRENCY
                ]);

                $result = array_values(array_filter($res, function ($value) {
                    return !isset($value['checking_error']);
                }));

                $final = [];

                foreach ($result as $key => $value) {
                    $final[$key]['domain_name'] = $value['domain_name'];
                    $final[$key]['is_available'] = $value['is_available'];
                    $final[$key]['price'] = $value['register_price'];
                }

                return [
                    'status' => true,
                    'message' => 'success',
                    'data' => $final
                ];

            } elseif ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION) {

                $res = $this->dreamScapeServiceProduction->request('domains/availability', 'GET', [
                    "domain_names" => $data,
                    "currency" => DomainManager::DEFAULT_CURRENCY
                ]);

                $result = array_values(array_filter($res, function ($value) {
                    return !isset($value['checking_error']);
                }));

                $final = [];

                foreach ($result as $key => $value) {
                    $final[$key]['domain_name'] = $value['domain_name'];
                    $final[$key]['is_available'] = $value['is_available'];
                    $final[$key]['price'] = $value['register_price'];
                }

                return [
                    'status' => true,
                    'message' => 'success',
                    'data' => $final
                ];

            } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_SANDBOX) {

                return $this->godaddyService->checkAvailableListDomain($data);

            } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_PRODUCTION) {

                return $this->godaddyServiceProduction->checkAvailableListDomain($data);

            }

        } catch (\Throwable $e) {

            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];

        }
    }

    /**
     * @param SystemDomain $domain
     * @param string $subdomain
     * @param string $type
     * @return array
     */
    public function checkAvailableSubDomain(SystemDomain $domain, string $subdomain, string $type = null): array
    {
        try {
            $type = $this->getType($type);

            if ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX) {

                // check sub domain is exist in dreamscape
                if ( $domain->domain_name == config('dreamScape.temp_domain_test') ) {
                    $listDNS = $this->getRecordDNS($domain->remote_domain_id, DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION);
                } else {
                    $listDNS = $this->getRecordDNS($domain->remote_domain_id, DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX);
                }

                foreach ($listDNS as $value) {
                    if ($value['subdomain'] == $subdomain) {
                        return [
                            'status' => true,
                            'message' => 'subdomain is exist',
                            'data' => [
                                'is_available' => false
                            ]
                        ];
                    }
                }

                return [
                    'status' => true,
                    'message' => 'success',
                    'data' => [
                        'is_available' => true
                    ]
                ];

            } elseif ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION) {

                // check sub domain is exist in vodien

                if ( $domain->domain_name == config('dreamScape.temp_domain_test') ) {
                    $listDNS = $this->getRecordDNS($domain->remote_domain_id, DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION);
                } else {
                    $listDNS = $this->getRecordDNS($domain->remote_domain_id, DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX);
                }

                foreach ($listDNS as $value) {
                    if ($value['subdomain'] == $subdomain) {
                        return [
                            'status' => true,
                            'message' => 'subdomain is exist',
                            'data' => [
                                'is_available' => false
                            ]
                        ];
                    }
                }

                return [
                    'status' => true,
                    'message' => 'success',
                    'data' => [
                        'is_available' => true
                    ]
                ];

            } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_SANDBOX) {

                $data = [
                    'domain_name' => explode('.', $domain->domain_name)[0] . '.' . explode('.', $domain->domain_name)[1],
                    'subdomain' => $subdomain,
                    'record_type' => 'A'
                ];

                if (explode('.', $domain->domain_name)[0] . '.' . explode('.', $domain->domain_name)[1] == config('godaddy.temp_domain_test')) {
                    return $this->godaddyServiceProduction->checkAvailableSubDomain($data);
                } else {
                    return $this->godaddyService->checkAvailableSubDomain($data);
                }

            } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_PRODUCTION) {

                $data = [
                    'domain_name' => explode('.', $domain->domain_name)[0] . '.' . explode('.', $domain->domain_name)[1],
                    'subdomain' => $subdomain,
                    'record_type' => 'A'
                ];

                return $this->godaddyServiceProduction->checkAvailableSubDomain($data);
            }
        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * @param int $remote_domain_id
     * @param string $currentType
     * @return array
     *
     */
    public function getRecordDNS(int $remote_domain_id, string $currentType = null): array
    {
        $type = $this->getType($currentType);

        if ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX) {
            return $this->dreamScapeService->request("domains/$remote_domain_id/dns", 'GET', [], []);

        } elseif ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION) {

            return $this->dreamScapeServiceProduction->request("domains/$remote_domain_id/dns", 'GET', [], []);

        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_SANDBOX) {

            $systemDomain = $this->getDomainByRemoteId($remote_domain_id);

            if ($systemDomain->domain_name == config('godaddy.temp_domain_test')) {
                return $this->godaddyServiceProduction->getRecordDNS($systemDomain->domain_name);
            } else {
                return $this->godaddyService->getRecordDNS($systemDomain->domain_name);
            }

            return null;

        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_PRODUCTION) {

            $systemDomain = $this->getDomainByRemoteId($remote_domain_id);
            return $this->godaddyServiceProduction->getRecordDNS($systemDomain->domain_name);
        }
    }

    /**
     * @param int $remote_domain_id
     * @param array $data
     * @param string $currentType
     * @return array
     *
     */
    public function createNewRecordDNS(int $remote_domain_id, array $data, string $currentType = null): array
    {
        $type = $this->getType($currentType);

        if ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX) {

            $systemDomain = $this->systemDomainManager->getSystemDomainByRemoteId($remote_domain_id);

            if ( isset($systemDomain) && $systemDomain->domain_name == config('dreamScape.temp_domain_test') ) {
                $record = $this->dreamScapeServiceProduction->request("domains/$remote_domain_id/dns", 'POST', [], $data);
            } else {
                $record = $this->dreamScapeService->request("domains/$remote_domain_id/dns", 'POST', [], $data);
            }

            $record['customer_id'] = config('dreamScape.customer_id_sandbox');
            $record['domain_register_from'] = DomainManager::PROVIDER_NAME_DREAMSCAPE;

            return $record;

        } elseif ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION) {

            $record = $this->dreamScapeServiceProduction->request("domains/$remote_domain_id/dns", 'POST', [], $data);

            $record['customer_id'] = config('dreamScape.customer_id_production');
            $record['domain_register_from'] = DomainManager::PROVIDER_NAME_DREAMSCAPE;

            return $record;

        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_SANDBOX) {

            $dataDNS = [
                "data" => $data['content'],
                "name" => $data['subdomain'],
                "ttl" => 600,
                "type" => "A",
                "weight" => 0
            ];

            $systemDomain = $this->systemDomainManager->getSystemDomainByRemoteId($remote_domain_id);
            $record = [];

            if (isset($systemDomain)) {
                if ($systemDomain->domain_name == config('godaddy.temp_domain_test')) {
                    $record = $this->godaddyServiceProduction->createNewRecordDNS($systemDomain->domain_name, $dataDNS);
                    $record['customer_id'] = config('godaddy.shopper_id_production');
                    $record['domain_register_from'] = DomainManager::PROVIDER_NAME_GODADDY;

                    return $record;
                } else {
                    $record = $this->godaddyService->createNewRecordDNS($systemDomain->domain_name, $dataDNS);
                    $record['customer_id'] = config('godaddy.shopper_id_sandbox');
                    $record['domain_register_from'] = DomainManager::PROVIDER_NAME_GODADDY;
                    return $record;
                }
            }

            return null;

        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_PRODUCTION) {

            $dataDNS = [
                "data" => $data['content'],
                "name" => $data['subdomain'],
                "ttl" => 600,
                "type" => "A",
                "weight" => 0
            ];

            $systemDomain = $this->systemDomainManager->getSystemDomainByRemoteId($remote_domain_id);
            $record = [];
            if (isset($systemDomain)) {
                $record = $this->godaddyServiceProduction->createNewRecordDNS($systemDomain->domain_name, $dataDNS);
            }

            $record['customer_id'] = config('godaddy.shopper_id_production');
            $record['domain_register_from'] = DomainManager::PROVIDER_NAME_GODADDY;
            return $record;
        }
    }

    /**
     * @param Domain $domain
     * @param SystemDomain $systemDomain
     * @param string $currentType
     * @return mixed
     *
     */
    public function createNewDomain(Domain $domain, SystemDomain $systemDomain = null, string $currentType = null)
    {
        // TODO: remove if production
        $ip = $systemDomain ? $systemDomain->ip : config('magicak.ip');

        $type = $this->getType($currentType);

        if ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX) {

            $customerId = config('dreamScape.customer_id_sandbox');

            $dataDomain = [
                "domain_name" => $domain->domain_name,
                "customer_id" => $customerId,
                "period" => $domain->domain_time * 12,
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

            $domainNew = $this->dreamScapeService->request('domains', 'POST', [], $dataDomain);
            $dataCreate = [
                'remote_domain_id' => $domainNew["id"],
                'domain_auth_key' => $domainNew['auth_key'],
                'customer_id' => $customerId,
                'domain_name' => $domain->domain_name,
                'registration_date' => Carbon::createFromTimeString($domainNew['start_date']),
                'expiration_date' => Carbon::createFromTimeString($domainNew['expiry_date']),
                'domain_type_id' => DomainManager::NEW_DOMAIN,
                'domain_register_from' => DomainManager::PROVIDER_NAME_DREAMSCAPE,
                'ip' => $ip,
                'status' => ""
            ];

            $this->signPointToDreamScape($domainNew["id"], $systemDomain);

            return $dataCreate;

        } elseif ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION) {

            $customerId = config('dreamScape.customer_id_production');

            $dataDomain = [
                "domain_name" => $domain->domain_name,
                "customer_id" => $customerId,
                "period" => $domain->domain_time * 12,
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

            $domainNew = $this->dreamScapeServiceProduction->request('domains', 'POST', [], $dataDomain);

            $dataCreate = [
                'remote_domain_id' => $domainNew["id"],
                'domain_auth_key' => $domainNew['auth_key'],
                'customer_id' => $customerId,
                'domain_name' => $domain->domain_name,
                'registration_date' => Carbon::createFromTimeString($domainNew['start_date']),
                'expiration_date' => Carbon::createFromTimeString($domainNew['expiry_date']),
                'domain_type_id' => DomainManager::NEW_DOMAIN,
                'domain_register_from' => DomainManager::PROVIDER_NAME_DREAMSCAPE,
                'ip' => $ip,
                'status' => ""
            ];

            $this->signPointToDreamScape($domainNew["id"], $systemDomain);

            return $dataCreate;

        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_SANDBOX) {

            $result = $this->godaddyService->createDomain([
                'hosting_ip' => $ip,
                'domain_name' => $domain->domain_name,
            ]);

            $dataCreate = [];

            if ($result['status']) {
                $record = $result['data'];
                $dataCreate = [
                    'remote_domain_id' => $record->id,
                    'domain_auth_key' => $record->auth_key,
                    'customer_id' => config('godaddy.shopper_id_sandbox'),
                    'domain_name' => $domain->domain_name,
                    'registration_date' => Carbon::createFromTimeString($record->created_at),
                    'expiration_date' => Carbon::createFromTimeString($record->expires_at),
                    'domain_type_id' => DomainManager::NEW_DOMAIN,
                    'domain_register_from' => DomainManager::PROVIDER_NAME_GODADDY,
                    'ip' => $ip,
                    'status' => ""
                ];
            } else {
                $customerId = config('dreamScape.customer_id_sandbox');

                $dataDomain = [
                    "domain_name" => $domain->domain_name,
                    "customer_id" => $customerId,
                    "period" => $domain->domain_time * 12,
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

                $domainNew = $this->dreamScapeService->request('domains', 'POST', [], $dataDomain);
                $dataCreate = [
                    'remote_domain_id' => $domainNew["id"],
                    'domain_auth_key' => $domainNew['auth_key'],
                    'customer_id' => $customerId,
                    'domain_name' => $domain->domain_name,
                    'registration_date' => Carbon::createFromTimeString($domainNew['start_date']),
                    'expiration_date' => Carbon::createFromTimeString($domainNew['expiry_date']),
                    'domain_type_id' => DomainManager::NEW_DOMAIN,
                    'domain_register_from' => DomainManager::PROVIDER_NAME_DREAMSCAPE,
                    'ip' => $ip,
                    'status' => ""
                ];

                $this->signPointToDreamScape($domainNew["id"], $systemDomain, DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX);

                return $dataCreate;
            }

            return $dataCreate;

        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_PRODUCTION) {

            $result = $this->godaddyService->createDomain([
                'hosting_ip' => $ip,
                'domain_name' => $domain->domain_name,
            ]);

            $dataCreate = [];

            if ($result['status']) {
                $record = $result['data'];
                $dataCreate = [
                    'remote_domain_id' => $record->id,
                    'domain_auth_key' => $record->auth_key,
                    'customer_id' => config('godaddy.shopper_id_production'),
                    'domain_name' => $domain->domain_name,
                    'registration_date' => Carbon::createFromTimeString($record->created_at),
                    'expiration_date' => Carbon::createFromTimeString($record->expires_at),
                    'domain_type_id' => DomainManager::NEW_DOMAIN,
                    'domain_register_from' => DomainManager::PROVIDER_NAME_GODADDY,
                    'ip' => $ip,
                    'status' => ""
                ];
            } else {
                $customerId = config('dreamScape.customer_id_production');

                $dataDomain = [
                    "domain_name" => $domain->domain_name,
                    "customer_id" => $customerId,
                    "period" => $domain->domain_time * 12,
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

                $domainNew = $this->dreamScapeService->request('domains', 'POST', [], $dataDomain);
                $dataCreate = [
                    'remote_domain_id' => $domainNew["id"],
                    'domain_auth_key' => $domainNew['auth_key'],
                    'customer_id' => $customerId,
                    'domain_name' => $domain->domain_name,
                    'registration_date' => Carbon::createFromTimeString($domainNew['start_date']),
                    'expiration_date' => Carbon::createFromTimeString($domainNew['expiry_date']),
                    'domain_type_id' => DomainManager::NEW_DOMAIN,
                    'domain_register_from' => DomainManager::PROVIDER_NAME_DREAMSCAPE,
                    'ip' => $ip,
                    'status' => ""
                ];

                $this->signPointToDreamScape($domainNew["id"], $systemDomain, DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION);

                return $dataCreate;
            }

            return $dataCreate;
        }
    }

    /**
     * @param array $data
     * @param string $type
     * @return array
     *
     */
    public function transferDomain(array $data, string $type): array
    {
        if ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX) {

            return $this->dreamScapeService->request("/domains/transfers/", 'POST', [], $data);

        } elseif ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION) {
            # code...
        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_SANDBOX) {
            # code...
        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_PRODUCTION) {
            # code...
        }
    }

    /**
     * @param int $remote_domain_id
     * @param int $record_id
     * @param string $type
     *
     */
    public function deleteRecordDNS(int $remote_domain_id, int $record_id, string $type = null)
    {
        $type = $this->getType($type);

        if ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX) {

            $domain = $this->systemDomainManager->getSystemDomainByRemoteId($remote_domain_id);

            if(isset($domain) && $domain->domain_name == config('dreamScape.temp_domain_test')){
                $this->dreamScapeServiceProduction->request("domains/$remote_domain_id/dns/$record_id", 'DELETE');
            } else {
                $this->dreamScapeService->request("domains/$remote_domain_id/dns/$record_id", 'DELETE');
            }

        } elseif (isset($domain) && $type == DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION) {

            $domain = $this->systemDomainManager->getSystemDomainByRemoteId($remote_domain_id);

            if($domain->domain_name == config('dreamScape.temp_domain_test')){
                $this->dreamScapeServiceProduction->request("domains/$remote_domain_id/dns/$record_id", 'DELETE');
            } else {
                $this->dreamScapeService->request("domains/$remote_domain_id/dns/$record_id", 'DELETE');
            }

        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_SANDBOX) {

            $systemDomain = $this->systemDomainManager->getSystemDomainByRemoteId($remote_domain_id);
            $domain = $this->getDomainByRemoteId($record_id);

            if ($systemDomain->domain_name == config('godaddy.temp_domain_test')) {
                $this->godaddyServiceProduction->deleteRecordDNS($systemDomain->domain_name, explode('.', $domain->domain_name)[0]);
            } else {
                $this->godaddyService->deleteRecordDNS($systemDomain->domain_name, explode('.', $domain->domain_name)[0]);
            }

        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_PRODUCTION) {

            $systemDomain = $this->systemDomainManager->getSystemDomainByRemoteId($remote_domain_id);
            $domain = $this->getDomainByRemoteId($record_id);

            if ($systemDomain->domain_name == config('godaddy.temp_domain_test')) {
                $this->godaddyServiceProduction->deleteRecordDNS($systemDomain->domain_name, explode('.', $domain->domain_name)[0]);
            } else {
                $this->godaddyService->deleteRecordDNS($systemDomain->domain_name, explode('.', $domain->domain_name)[0]);
            }

        }
    }

    /**
     * @param int $domain_id
     */
    public function deleteDomain(int $domain_id)
    {
        $this->destroyDomain($domain_id, [
            'deleted_id' => auth()->id()
        ]);
    }


    /**
     * @param int $remote_domain_id
     * @param int $record_id
     * @param array $data
     * @param string $type
     *
     */
    public function updateRecordDNS(int $remote_domain_id, int $record_id, array $data, string $type)
    {
        if ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX) {

            $this->dreamScapeService->request("domains/$remote_domain_id/dns/$record_id", 'PATCH', [], $data);

        } elseif ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION) {

            return $this->dreamScapeServiceProduction->request("domains/$remote_domain_id/dns/$record_id", 'PATCH', [], $data);

        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_SANDBOX) {

            $systemDomain = $this->systemDomainManager->getSystemDomainByRemoteId($remote_domain_id);
            $domain = $this->getDomainByRemoteId($record_id);

            return $this->godaddyService->updateRecordDNS($systemDomain->domain_name, $domain->domain_name, $data);

        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_PRODUCTION) {

            $systemDomain = $this->systemDomainManager->getSystemDomainByRemoteId($remote_domain_id);
            $domain = $this->getDomainByRemoteId($record_id);

            if ($systemDomain->domain_name == config('godaddy.temp_domain_test')) {
                return $this->godaddyServiceProduction->updateRecordDNS($systemDomain->domain_name, $domain->domain_name, $data);
            } else {
                return $this->godaddyService->updateRecordDNS($systemDomain->domain_name, $domain->domain_name, $data);
            }
        }
    }

    /**
     * @param int $remote_domain_id
     */
    public function getDomainByRemoteId(int $remote_domain_id)
    {
        return $this->model->where('remote_domain_id', $remote_domain_id)->first();
    }

    /**
     * @param Domain $domain
     * @param SystemDomain $systemDomain
     * @param string $type
     * @return mixed
     */
    public function updateDomainForUserWebsite(Domain $domain, SystemDomain $systemDomain, string $type = null)
    {
        $environment = strtolower(config('app.env')) === 'production' ? '_production' : '_sandbox';
        $type = $domain->domain_register_from . $environment;

        if ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX) {

            $this->signPointToDreamScape($domain->remote_domain_id, $systemDomain, $type);

        } elseif ($type == DomainManager::PROVIDER_NAME_DREAMSCAPE_PRODUCTION) {

            $this->signPointToDreamScape($domain->remote_domain_id, $systemDomain, $type);

        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_SANDBOX) {

            $dataRecordRoot = [
                'type' => strtoupper(DomainManager::DOMAIN_TYPE_A),
                'name' => DomainManager::SUB_DOMAIN_ROOT,
                'data' => $systemDomain ? $systemDomain->ip : config('magicak.ip'),
                'ttl' => 600,
                "weight" => 0
            ];

            $this->godaddyService->updateRecordDNS($domain->domain_name, DomainManager::SUB_DOMAIN_ROOT, $dataRecordRoot);

            $dataRecordWWW = [
                'type' => strtoupper(DomainManager::DOMAIN_TYPE_CNAME),
                'name' => DomainManager::SUB_DOMAIN_WWW,
                'data' => DomainManager::SUB_DOMAIN_ROOT,
                'ttl' => 600,
                "weight" => 0
            ];
            $this->godaddyService->updateRecordDNS($domain->domain_name, DomainManager::SUB_DOMAIN_WWW, $dataRecordWWW, DomainManager::DOMAIN_TYPE_CNAME);
        } elseif ($type == DomainManager::PROVIDER_NAME_GODADDY_PRODUCTION) {

            $dataRecordRoot = [
                'type' => strtoupper(DomainManager::DOMAIN_TYPE_A),
                'name' => DomainManager::SUB_DOMAIN_ROOT,
                'data' => $systemDomain ? $systemDomain->ip : config('magicak.ip'),
                'ttl' => 600,
                "weight" => 0
            ];

            $this->godaddyServiceProduction->updateRecordDNS($domain->domain_name, DomainManager::SUB_DOMAIN_ROOT, $dataRecordRoot);

            $dataRecordWWW = [
                'type' => strtoupper(DomainManager::DOMAIN_TYPE_CNAME),
                'name' => DomainManager::SUB_DOMAIN_WWW,
                'data' => DomainManager::SUB_DOMAIN_ROOT,
                'ttl' => 600,
                "weight" => 0
            ];
            $this->godaddyServiceProduction->updateRecordDNS($domain->domain_name, DomainManager::SUB_DOMAIN_WWW, $dataRecordWWW, DomainManager::DOMAIN_TYPE_CNAME);
        }
    }
}
