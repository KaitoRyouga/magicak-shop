<?php

namespace App\Managers;

use App\Repositories\TemporaryDomainRepository;
use App\Repositories\SystemDomainRepository;
use App\Models\TemporaryDomain;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Queue;

class TemporaryDomainManager extends BaseManager
{

    const TEMPORARY_DOMAIN_STATUS_IN_USE = 'In Used';
    const TEMPORARY_DOMAIN_STATUS_AVAILABLE = 'Available';
    const TEMPORARY_DOMAIN_STATUS_UNKNOWN = 'Unknown';
    const TEMPORARY_DOMAIN_STATUS_NO_CERT = 'No Cert';
    const ADD_MORE_TEMPORARY_DOMAIN = 'add_more_temporary_domain';

    /**
     * @var TemporaryDomainRepository
     */
    protected $temporaryDomainRepository;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var SystemDomainRepository
     */
    protected $systemDomainRepository;

    /**
     * TemporaryDomainManager constructor.
     * @param TemporaryDomainRepository $temporaryDomainRepository
     * @param Client $client
     * @param SystemDomainRepository $systemDomainRepository
     *
     */
    public function __construct(
        TemporaryDomainRepository $temporaryDomainRepository,
        Client $client,
        SystemDomainRepository $systemDomainRepository
    ) {
        $this->temporaryDomainRepository = $temporaryDomainRepository;
        $this->client = $client;
        $this->systemDomainRepository = $systemDomainRepository;
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getType($type)
    {
        $environment = strtolower(config('app.env')) === 'production' ? '_production' : '_sandbox';
        $provider = $type . $environment;

        return $provider;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function checkStatusTemporaryDomain(array $params)
    {
        try {
            $data = $this->temporaryDomainRepository->getTemporaryDomainPaginate($params);

            foreach ($data as $value) {

                if ($value->userWebsite()->exists() && $value->available == 0) {
                    $status = self::TEMPORARY_DOMAIN_STATUS_IN_USE;
                } else {

                    try {
                        $response = $this->client->request('GET', $value->domain_name);
                        $code = $response->getStatusCode();

                        if ($code == 200) {
                            $status = self::TEMPORARY_DOMAIN_STATUS_AVAILABLE;
                        } elseif ($code == 401) {
                            $status = self::TEMPORARY_DOMAIN_STATUS_NO_CERT;
                        } else {
                            $status = self::TEMPORARY_DOMAIN_STATUS_UNKNOWN;
                        }
                    } catch (\Throwable $e) {
                        $status = self::TEMPORARY_DOMAIN_STATUS_UNKNOWN;
                    }
                }

                $value->status = $status;
                $value->save();
            }

            return [
                'code' => 200,
                'data' => $data,
                'message' => 'Check status temporary domain successfully'
            ];
        } catch (\Throwable $e) {

            return [
                'code' => 500,
                'data' => null,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getAllTemporaryDomainWithSystemDomain(array $data)
    {
        try {
            $result = $this->temporaryDomainRepository->getAllTemporaryDomainWithUserWebsite($data);

            return [
                'code' => 200,
                'data' => $result,
                'message' => 'Get all temporary domain with system domain successfully'
            ];
        } catch (\Throwable $e) {
            return [
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * @return mixed
     */
    public function getManageTemporaryDomain()
    {
        try {

            $data = $this->systemDomainRepository->getSystemDomainByManageTemporaryDomain();

            foreach ($data as $value) {
                $count = $this->temporaryDomainRepository->getTemporaryDomainWithSystemDomain([
                    'domain_name' => $value->domain_name
                ])->count();
                $rate = $this->temporaryDomainRepository->getTemporaryDomainNotAvailableWithSystemDomain([
                    'domain_name' => $value->domain_name
                ])->count();

                $value->available = $rate . '/' . $count;

                $value->location = "";
                if (count($value->hostingCluster) != 0) {
                    $value->location = $value->hostingCluster[0]->dcLocation->location;
                }
            }

            return [
                'code' => 200,
                'data' => $data,
                'message' => 'Get manage temporary domain successfully'
            ];
        } catch (\Throwable $e) {
            return [
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * @param array $data
     * @return ?TemporaryDomain
     */
    public function getTemporaryDomainAvailable(array $data): ?TemporaryDomain
    {
        return $this->temporaryDomainRepository->getTemporaryDomainAvailable($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return TemporaryDomain
     */
    public function changeAvailable(int $id, array $data): TemporaryDomain
    {
        return $this->temporaryDomainRepository->changeAvailable($id, $data);
    }

    /**
     * @param string $domainName
     * @return TemporaryDomain|null
     */
    public function getDomainByName(string $domain_name): ?TemporaryDomain
    {
        return $this->temporaryDomainRepository->getDomainByName($domain_name);
    }

    /**
     * @param array $data
     * @return array
     */
    public function updateTemporaryDomainWithSystemDomain(array $data): array
    {
        try {
            $systemDomain = $this->systemDomainRepository->checkSystemDomainExist($data);
            if (!isset($systemDomain)) {
                return [
                    'code' => 400,
                    'message' => "System Domain doesn't exist",
                    'data' => null
                ];
            }

            $listTemporaryDomain = $this->temporaryDomainRepository->getTemporaryDomainPaginate($data);

            $provider = $this->getType($systemDomain->domain_register_from);

            if(isset($systemDomain)) {
                $result = $this->temporaryDomainRepository->updateSystemdomainWithIP($systemDomain, $data, $provider);

                if (!$result) {
                    return [
                        'code' => 400,
                        'message' => $result['message'],
                        'data' => null
                    ];
                }
            }

            foreach ($listTemporaryDomain as $value) {

                $result = $this->temporaryDomainRepository->updateTemporaryDomainWithIP($value, $data, $provider, $systemDomain);

                if (!$result) {
                    return [
                        'code' => 400,
                        'message' => $result['message'],
                        'data' => null
                    ];
                }
            }

            return [
                'code' => 200,
                'message' => 'Update Successfully',
                'data' => null
            ];

        } catch (\Throwable $e) {
            return [
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function addMoreTemporaryDomain(array $data): array
    {
        try {
            $systemDomain = $this->systemDomainRepository->checkSystemDomainExist($data);
            $data['ip'] = $systemDomain->ip;

            if (!isset($systemDomain)) {
                return [
                    'code' => 400,
                    'message' => "System Domain doesn't exist",
                    'data' => null
                ];
            }

            $listTemporaryDomain = $this->temporaryDomainRepository->getTemporaryDomainWithSystemDomain($data);

            $provider = $this->getType($systemDomain->domain_register_from);

            for ($i = $listTemporaryDomain->count() + 1; $i < $listTemporaryDomain->count() + 22; $i++) {

                $result = $this->temporaryDomainRepository->addMoreTemporaryDomainWithIP($i, $data, $provider, $systemDomain, $systemDomain->hostingCluster[0]->dcLocation->id);

                if (!$result) {
                    return [
                        'code' => 400,
                        'message' => $result['message'],
                        'data' => null
                    ];
                } else {
                    self::pushAddMoreTemporaryDomainMessageData('temp' . $i . '.' . $systemDomain->domain_name);
                }
            }



            return [
                'code' => 200,
                'message' => 'Add more Successfully',
                'data' => null
            ];

        } catch (\Throwable $e) {
            return [
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * @param String $domain_name
     */
    public function pushAddMoreTemporaryDomainMessageData(String $domain_name): void
    {
        $data = [
            'current_tasks' => self::ADD_MORE_TEMPORARY_DOMAIN,
            'subdomain' => explode('.', $domain_name)[0],
            'domain_name' => $domain_name
        ];

        Queue::pushRaw(json_encode($data));
    }

}
