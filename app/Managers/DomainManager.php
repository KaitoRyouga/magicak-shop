<?php

namespace App\Managers;

use App\Repositories\DomainTypeRepository;
use App\Repositories\DomainRepository;
use App\Repositories\SystemDomainRepository;
use App\Validators\DomainValidator;
use App\Models\Domain;
use App\Models\SystemDomain;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DomainManager extends BaseManager
{
    const LIST_TYPE_DOMAIN = ['com', 'net', 'org', 'info'];
    const DEFAULT_CURRENCY = 'USD';
    const DEFAULT_PERIOD_MAGICAK_DOMAIN = 12;

    const NEW_DOMAIN = 1;
    const SUB_DOMAIN = 2;
    const OWN_DOMAIN = 3;

    const DOMAIN_TYPE_A = 'a';
    const DOMAIN_TYPE_CNAME = 'cname';
    const SUB_DOMAIN_ROOT = '@';
    const SUB_DOMAIN_WWW = 'www';
    const STATUS_DOMAIN_IN_USED = 'In Used';
    const STATUS_DOMAIN_NOT_IN_USED = 'Not In Used';
    const STATUS_DOMAIN_EXPIRED = 'Expired';
    const STATUS_DOMAIN_TRANSFERING = 'TransferIng';
    const STATUS_DOMAIN_UNLIMITED = 'Unlimited';
    const STATUS_DOMAIN_UNKNOWN = 'Unknown';

    const CREATE_DOMAIN_IN_USER_WEBSITE = 1;
    const CREATE_DOMAIN_IN_MANAGE_DOMAIN = 2;

    const PROVIDER_NAME_DREAMSCAPE_SANDBOX = 'dreamscape_sandbox';
    const PROVIDER_NAME_DREAMSCAPE_PRODUCTION = 'dreamscape_production';
    const PROVIDER_NAME_GODADDY_SANDBOX = 'godaddy_sandbox';
    const PROVIDER_NAME_GODADDY_PRODUCTION = 'godaddy_production';
    const PROVIDER_NAME_DREAMSCAPE = 'dreamscape';
    const PROVIDER_NAME_GODADDY = 'godaddy';

    /**
     * @var SocketManager
     */
    protected $socketManager;

    /**
     * @var DomainTypeRepository
     */
    protected $domainTypeRepository;

    /**
     * @var DomainRepository
     */
    protected $domainRepository;

    /**
     * @var SystemDomainRepository
     */
    protected $systemDomainRepository;

    /**
     * @var DomainValidator
     */
    protected $domainValidator;

    /**
     * DomainManager constructor.
     * @param SocketManager $socketManager
     * @param DomainTypeRepository $domainTypeRepository
     * @param DomainRepository $domainRepository
     * @param SystemDomainRepository $systemDomainRepository
     * @param DomainValidator $domainValidator
     *
     */
    public function __construct(
        SocketManager $socketManager,
        DomainTypeRepository $domainTypeRepository,
        DomainRepository $domainRepository,
        SystemDomainRepository $systemDomainRepository,
        DomainValidator $domainValidator
    ) {
        $this->socketManager = $socketManager;
        $this->domainTypeRepository = $domainTypeRepository;
        $this->domainRepository = $domainRepository;
        $this->systemDomainRepository = $systemDomainRepository;
        $this->domainValidator = $domainValidator;
    }

    /**
     * @param int $id
     * @return ?Domain
     * @throws \Exception
     */
    public function getDomainById(int $id) : ?Domain
    {
        try {
            return $this->domainRepository->getDomainById([
                'id' => $id
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param array $data
     * @return ?Domain
     * @throws \Exception
     */
    public function updateOrCreateDomain(array $data): ?Domain
    {
        try {

            // check domain existed
            $domain = $this->domainRepository->checkDomainNameExist($data);

            // get domain id if existed
            $domainId = empty($domain) ? null : $domain->id;

            $dataCreate = [
                'domain_name' => $data['domain_name'],
                'domain_time' => $data['domain_time'],
                'domain_type_id' => $data['domain_type_id'],
                'is_transfer' => $data['is_transfer'] ?? null,
                'domain_auth_key' => $data['domain_auth_key'] ?? null,
                'price' => round($data['total_price'] ?? 0, 2) ?? 0,
                'active' => 1,
                'created_id' => Auth::id(),
                'domain_register_from' => $data['domain_register_from'] ?? DomainManager::PROVIDER_NAME_GODADDY,
            ];

            return $this->domainRepository->updateOrCreateDomain($domainId, $dataCreate);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function checkDomainNameExist(array $data)
    {
        // validate domain name
        $validator = $this->domainValidator->validateCheckDomainNameExist($data);
        if (!$validator['status']) {
            return [
                'code' => 400,
                'message' => $validator['message'],
                'data' => null
            ];
        }

        try {
            // if domain is new domain
            if ( $data['domain_type_id'] == self::NEW_DOMAIN )
            {
                // append domain name with domain type
                $domain = explode('.', $data['domain_name'])[0];
                $topDomain = explode('.', $data['domain_name'])[1];

                $listDomain = [
                    $data['domain_name']
                ];

                foreach (self::LIST_TYPE_DOMAIN as $value) {
                    if ($value != $topDomain) {
                        $listDomain[] = $domain . '.' . $value;
                    }
                }

                $result = $this->domainRepository->checkAvailableListDomain($listDomain);
                return [
                    'code' => $result['status'] ? 200 : 400,
                    'message' => $result['message'],
                    'data' => $result['data']
                ];

            } else if ( $data['domain_type_id'] == self::SUB_DOMAIN ) {

                // check domain name is sub domain
                $subdomain = explode('.', $data['domain_name'])[0];
                $domain = explode('.', $data['domain_name'])[1];
                $topDomain = explode('.', $data['domain_name'])[2];

                $systemDomain = $domain . '.' . $topDomain;

                $domainName = $this->systemDomainRepository->checkSystemDomainExist([
                    'domain_name' => $systemDomain
                ]);

                $result = $this->domainRepository->checkAvailableSubDomain($domainName, $subdomain);

                if ($domainName) {
                    return [
                        'code' => $result['status'] ? 200 : 400,
                        'message' => $result['message'],
                        'data' => $result['data']
                    ];
                } else {
                    return [
                        'code' => 400,
                        'message' => "Domain name is invalid",
                        'data' => null
                    ];
                }
            }
        } catch (\Throwable $e) {
            return [
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }

        return $this->domainRepository->checkDomainNameExist($data);
    }

    /**
     * @param int $type
     * @param Domain $domain
     * @param SystemDomain $systemDomain
     * @return mixed
     * @throws \Exception
     */
    public function registerDomain(int $type, Domain $domain, SystemDomain $systemDomain = null)
    {
        try {
            $dataCreate = [];

            // if new domain => call register
            if ($domain->domain_type_id == self::NEW_DOMAIN) {

                $dataCreate = $this->domainRepository->createNewDomain($domain, $systemDomain);

            } else if ($domain->domain_type_id == self::SUB_DOMAIN) { // set dns sub domain

                $dataDNS = [
                    "type" => self::DOMAIN_TYPE_A,
                    "content" => $systemDomain ? $systemDomain->ip : config('magicak.ip'),
                    "subdomain" => explode('.', $domain->domain_name)[0],
                ];

                $record = $this->domainRepository->createNewRecordDNS($systemDomain->remote_domain_id, $dataDNS);

                $dataCreate = [
                    'domain_name' => $domain->domain_name,
                    'remote_domain_id' => $record['id'],
                    'customer_id' => $record['customer_id'],
                    'domain_register_from' => $record['domain_register_from'],
                    'ip' => $systemDomain ? $systemDomain->ip : config('magicak.ip')
                ];
            }

            // define type for domain
            if ($type == self::CREATE_DOMAIN_IN_MANAGE_DOMAIN) {
                $typeFinal = "create_domain_in_manage_domain";
                $dataCreate['status'] = "";
            } elseif ($type == self::CREATE_DOMAIN_IN_USER_WEBSITE) {
                $typeFinal = "create_domain_in_user_website";
            }

            $this->socketManager->pushToMessageSocket([
                "message" => "Create domain success.",
                "data" => [
                    "domain_name" => $dataCreate['domain_name'],
                    "expiration_date" => isset($dataCreate['expiration_date']) ? Carbon::parse($dataCreate['expiration_date'])->format('Y-m-d') : 'Unlimited',
                    "status" => $type == self::CREATE_DOMAIN_IN_MANAGE_DOMAIN ? 'Not In Used' : ''
                ],
                "type" => $typeFinal
            ]);

            $result = $this->domainRepository->updateOrCreate($domain->id, $dataCreate);

            return [
                'status' => true,
                'data' => $result,
                'message' => 'Create domain success.'
            ];
        } catch (\Throwable $e) {
            return [
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * @param int $remote_domain_id
     * @param int $domain_id
     *
     */
    public function deleteRecordDNS(int $remote_domain_id, int $domain_id)
    {
        $this->domainRepository->deleteRecordDNS($remote_domain_id, $domain_id);
    }

    /**
     * @param int $domain_id
     * @param string $type
     * @return array
     *
     */
    public function getRecordDNS(int $domain_id, string $type): array
    {
        return $this->domainRepository->getRecordDNS($domain_id, $type);
    }

    /**
     * @param int $domain_id
     * @param int $record_id
     * @param array $data
     * @param string $type
     * @return array
     *
     */
    public function updateRecordDNS(int $domain_id, int $record_id, array $data, string $type): array
    {
        return $this->domainRepository->updateRecordDNS($domain_id, $record_id, $data, $type);
    }

    /**
     * @param int $remote_domain_id
     * @param array $data
     * @param string $type
     * @return array
     *
     */
    public function createNewRecordDNS(int $remote_domain_id, array $data, string $type): array
    {
        return $this->domainRepository->createNewRecordDNS($remote_domain_id, $data, $type);
    }

    /**
     * @param int $domain_id
     * @param int $remote_domain_id
     */
    public function deleteDomain(int $domain_id): void
    {
        $this->domainRepository->deleteDomain($domain_id);
    }

    /**
     * @param string $domain_name
     * @return Domain|null
     */
    public function getDomainByName(string $domain_name): ?Domain
    {
        return $this->domainRepository->getDomainByName($domain_name);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getDomains(): LengthAwarePaginator
    {
        try {
            $data = $this->domainRepository->getDomains(false);

            foreach ($data as $key => $value) {

                $status = "";

                // check status in used
                if ($value->userWebsite()->exists()) {
                    $status = self::STATUS_DOMAIN_IN_USED;
                } else {
                    $status = self::STATUS_DOMAIN_NOT_IN_USED;
                }

                // check status is transfer
                if ($value->is_transfer == 1) {
                    $status = self::STATUS_DOMAIN_TRANSFERING;
                }

                // check status is expired
                if ($value->expiration_date < Carbon::now() && $value->domain_type_id != self::SUB_DOMAIN && isset($value->expiration_date)) {
                    $status = self::STATUS_DOMAIN_EXPIRED;
                }

                // check status is sub domain
                if ($value->domain_type_id == self::SUB_DOMAIN) {
                    $status = self::STATUS_DOMAIN_UNLIMITED;
                }

                if(!isset($value->expiration_date) && $value->domain_type_id != self::SUB_DOMAIN) {
                    $status = self::STATUS_DOMAIN_UNKNOWN;
                }

                if (!empty($value->status)) {
                    $status = ucwords(join(' ', explode("_", $value->status)));
                }

                $data[$key]->status = $status;
                $data[$key]->business_name = isset($value->userWebsite()->first()->business_name) ? $value->userWebsite()->first()->business_name : '';
            }

            return $data;
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * @return array
     */
    public function getDomainsByAdmin(): array
    {
        try {
            $data = $this->domainRepository->getDomains(true);

            foreach ($data as $key => $value) {

                $status = "";

                // check status in used
                if ($value->userWebsite()->exists()) {
                    $status = self::STATUS_DOMAIN_IN_USED;
                } else {
                    $status = self::STATUS_DOMAIN_NOT_IN_USED;
                }

                // check status is transfer
                if ($value->is_transfer == 1) {
                    $status = self::STATUS_DOMAIN_TRANSFERING;
                }

                // check status is expired
                if ($value->expiration_date < Carbon::now() && $value->domain_type_id != self::SUB_DOMAIN && isset($value->expiration_date)) {
                    $status = self::STATUS_DOMAIN_EXPIRED;
                }

                // check status is sub domain
                if ($value->domain_type_id == self::SUB_DOMAIN) {
                    $status = self::STATUS_DOMAIN_UNLIMITED;
                }

                if(!isset($value->expiration_date) && $value->domain_type_id != self::SUB_DOMAIN) {
                    $status = self::STATUS_DOMAIN_UNKNOWN;
                }

                if (!empty($value->status)) {
                    $status = ucwords(join(' ', explode("_", $value->status)));
                }

                $data[$key]->status = $status;
                $data[$key]->business_name = isset($value->userWebsite()->first()->business_name) ? $value->userWebsite()->first()->business_name : '';
            }

            return [
                "code" => 200,
                "message" => "get domains success",
                "data" => $data
            ];
        } catch (\Throwable $e) {
            return [
                "code" => 500,
                "message" => $e->getMessage(),
                "data" => []
            ];
        }
    }

    /**
     * @return array
     */
    public function getListDomainUpdate(): array
    {
        try {
            $data = $this->domainRepository->getDomains(false);
            $listDomainUpdate = [];

            foreach ($data as $value) {

                // check status in used
                if (
                    !$value->userWebsite()->exists() &&
                    $value->expiration_date > Carbon::now() &&
                    $value->deleted_at == null &&
                    $value->domain_type_id != self::SUB_DOMAIN
                ) {
                    $listDomainUpdate[] = $value;
                }
            }

            return $listDomainUpdate;
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function deleteDomainByManageDomain(array $data): array
    {
        try {
            $domain = $this->domainRepository->getDomainById($data);

            if ($domain->userWebsite()->exists()) {
                return [
                    'data' => null,
                    'message' => 'Domain is in used',
                    'code' => 400
                ];
            }

            // check if domain type is subdomain
            if ($domain->domain_type_id == self::SUB_DOMAIN) {

                $id = $domain->remote_domain_id;
                $systemDomain = $this->systemDomainRepository->checkSystemDomainExist([
                    'domain_name' => explode('.', $domain->domain_name)[1] . '.' . explode('.', $domain->domain_name)[2]
                ]);

                $remote_domain_id = $systemDomain->remote_domain_id;

                $this->deleteRecordDNS($remote_domain_id, $id);
                $this->deleteDomain($domain->id);

            } elseif ($domain->domain_type_id == self::NEW_DOMAIN) { // check if domain type is new domain

                $this->deleteDomain($domain->id);
            }

            return [
                'data' => null,
                'message' => 'Domain has been deleted',
                'code' => 200
            ];
        } catch (\Throwable $e) {
            return [
                'data' => null,
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function triggerInitDomain(array $data): array
    {

        try {

            $domain = $this->domainRepository->getDomainByName($data['domain_name']);

            if (!$domain || $domain->status != "") {
                return [
                    'data' => null,
                    'message' => 'Domain not found',
                    'code' => 400
                ];
            }

            $this->registerDomain(self::CREATE_DOMAIN_IN_MANAGE_DOMAIN, $domain);

            return [
                'data' => null,
                'message' => 'Domain has been created',
                'code' => 200
            ];
        } catch (\Throwable $e) {
            return [
                'data' => null,
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }

    /**
     * @param int $remote_domain_id
     * @return Domain|null
     */
    public function getDomainByRemoteId(int $remote_domain_id): ?Domain
    {
        return $this->domainRepository->getDomainByRemoteId($remote_domain_id);
    }

    /**
     * @param Domain $domain
     * @param SystemDomain $systemDomain
     * @param string $type
     * @return mixed
     */
    public function updateDomainForUserWebsite(Domain $domain, SystemDomain $systemDomain, string $type = null)
    {
        return $this->domainRepository->updateDomainForUserWebsite($domain, $systemDomain, $type);
    }
}
