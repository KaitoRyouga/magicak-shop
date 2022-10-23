<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Managers\DomainManager;

class GodaddyServiceProduction
{
    const API_URL_PRODUCTION = 'https://api.godaddy.com/';
    const API_VERSION = 'v1';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $httpHeaders = [];


    /**
     * Constructor.
     */
    public function __construct(
        Client $client
    ) {
        $this->client = $client;
        $this->httpHeaders = [
            'accept' => 'application/json',
            'Authorization' => 'sso-key ' . config('godaddy.api_key_production') . ':' . config('godaddy.secret_key_production'),
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function checkAvailableListDomain(array $data): array
    {
        try {
            $response = $this->client->post(self::API_URL_PRODUCTION . self::API_VERSION . '/domains/available', [
                'params' => [
                    'checkType' => 'FAST',
                    'forTransfer' => 'false',
                ],
                'json' => $data,
                'headers' => $this->httpHeaders
            ]);

            if ($response->getStatusCode() == 200) {
                $content = $response->getBody()->getContents();
                $output = json_decode($content);

                $result = [];

                // TODO: fix later => currency
                if (!empty($output)) {
                    foreach ($output->domains as $key => $value) {
                        $result[$key]['is_available'] = $value->available;
                        $result[$key]['price'] = $value->available ? round($value->price / 16856) : 0;
                        $result[$key]['domain_name'] = $value->domain;
                    }
                }

                return [
                    'status' => true,
                    'message' => 'success',
                    'data' => $result
                ];
            }

            return [
                'status' => true,
                'message' => 'error',
                'data' => null
            ];

        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => trim(str_replace(['"', '}'], "", explode(':', $e->getMessage())[count(explode(':', $e->getMessage())) - 1])),
                'data' => null
            ];
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function checkAvailableSubDomain(array $data): array
    {
        try {
            $url = self::API_URL_PRODUCTION . self::API_VERSION . '/domains/' . $data['domain_name'] . '/records/' . $data['record_type'] . '/' . $data['subdomain'];
            $response = $this->client->get($url, [
                'params' => [
                    'X-Shopper-Id' => config('godaddy.shopper_id_production'),
                ],
                'headers' => $this->httpHeaders
            ]);

            if ($response->getStatusCode() == 200) {
                $content = $response->getBody()->getContents();
                $output = json_decode($content);

                if (!empty($output)) {
                    return [
                        'status' => true,
                        'message' => 'Subdomain already exists',
                        'data' => [
                            'is_available' => false
                        ]
                    ];
                }
            }

            return [
                'status' => true,
                'message' => 'Subdomain is available',
                'data' => [
                    'is_available' => true
                ]
            ];

        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => trim(str_replace(['"', '}'], "", explode(':', $e->getMessage())[count(explode(':', $e->getMessage())) - 1])),
                'data' => null
            ];
        }
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createDomain(array $data)
    {
        try {
            // TODO: remove if production
            $data = [
                "consent" => [
                    "agreementKeys" => [
                        "DNRA"
                    ],
                    "agreedAt" => Carbon::now()->toISOString(),
                    "agreedBy" => $data['hosting_ip']
                ],
                "contactAdmin" => [
                    "addressMailing" => [
                        "address1" => "139 xuan hong",
                        "address2" => "",
                        "city" => "Ho chi minh City",
                        "country" => "VN",
                        "postalCode" => "70000",
                        "state" => "Ho chi minh City"
                    ],
                    "email" => "nguyenthanhtien.kr0407@gmail.com",
                    "fax" => "+1.5127654321",
                    "jobTitle" => "Owner",
                    "nameFirst" => "Tien",
                    "nameLast" => "Nguyen",
                    "nameMiddle" => "Thanh",
                    "organization" => "Magicak",
                    "phone" => "+1.5127654321"
                ],
                "contactBilling" => [
                    "addressMailing" => [
                        "address1" => "139 xuan hong",
                        "address2" => "",
                        "city" => "Ho chi minh City",
                        "country" => "VN",
                        "postalCode" => "70000",
                        "state" => "Ho chi minh City"
                    ],
                    "email" => "nguyenthanhtien.kr0407@gmail.com",
                    "fax" => "+1.5127654321",
                    "jobTitle" => "Owner",
                    "nameFirst" => "Tien",
                    "nameLast" => "Nguyen",
                    "nameMiddle" => "Thanh",
                    "organization" => "Magicak",
                    "phone" => "+1.5127654321"
                ],
                "contactRegistrant" => [
                    "addressMailing" => [
                        "address1" => "139 xuan hong",
                        "address2" => "",
                        "city" => "Ho chi minh City",
                        "country" => "VN",
                        "postalCode" => "70000",
                        "state" => "Ho chi minh City"
                    ],
                    "email" => "nguyenthanhtien.kr0407@gmail.com",
                    "fax" => "+1.5127654321",
                    "jobTitle" => "Owner",
                    "nameFirst" => "Tien",
                    "nameLast" => "Nguyen",
                    "nameMiddle" => "Thanh",
                    "organization" => "Magicak",
                    "phone" => "+1.5127654321"
                ],
                "contactTech" => [
                    "addressMailing" => [
                        "address1" => "139 xuan hong",
                        "address2" => "",
                        "city" => "Ho chi minh City",
                        "country" => "VN",
                        "postalCode" => "70000",
                        "state" => "Ho chi minh City"
                    ],
                    "email" => "nguyenthanhtien.kr0407@gmail.com",
                    "fax" => "+1.5127654321",
                    "jobTitle" => "Owner",
                    "nameFirst" => "Tien",
                    "nameLast" => "Nguyen",
                    "nameMiddle" => "Thanh",
                    "organization" => "Magicak",
                    "phone" => "+1.5127654321"
                ],
                "domain" => $data["domain_name"],
                "nameServers" => [
                    "ns47.domaincontrol.com",
                    "ns48.domaincontrol.com"
                ],
                "period" => 1,
                "privacy" => false,
                "renewAuto" => false
            ];

            $response = $this->client->post(self::API_URL_PRODUCTION . self::API_VERSION . '/domains/purchase', [
                'params' => [
                    'X-Shopper-Id' => config('godaddy.shopper_id_sandbox'),
                ],
                'json' => $data,
                'headers' => $this->httpHeaders
            ]);

            if ($response->getStatusCode() == 200) {
                $content = $response->getBody()->getContents();
                $output = json_decode($content);

                $dataDetail = $this->getDetailDomain($data['domain_name']);

                $dataCreate = [
                    'id' => $dataDetail->domainId,
                    'price' => $output['total'],
                    'auth_key' => $output['authCode'],
                    'created_at' =>$dataDetail->createdAt,
                    'expires_at' => $dataDetail->expires,
                ];

                return [
                    'status' => true,
                    'message' => 'Create domain successfully',
                    'data' => $dataCreate
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => trim(str_replace(['"', '}'], "", explode(':', $e->getMessage())[count(explode(':', $e->getMessage())) - 1])),
                'data' => null
            ];
        }
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getDetailDomain(array $data)
    {
        $url = self::API_URL_PRODUCTION . self::API_VERSION . '/domains/' . $data['domain_name'];
        $response = $this->client->get($url, [
            'params' => [
                'X-Shopper-Id' => config('godaddy.shopper_id_production'),
            ],
            'headers' => $this->httpHeaders
        ]);

        if ($response->getStatusCode() == 200) {
            $content = $response->getBody()->getContents();
            $output = json_decode($content);

            if (!empty($output)) {
                return $output;
            }
        }

        return null;
    }

    /**
     * @param string $domain_name
     * @param array $data
     * @return mixed
     */
    public function createNewRecordDNS(string $domain_name, array $data)
    {
        $url = self::API_URL_PRODUCTION . self::API_VERSION . '/domains/' . $domain_name . '/records';

        $response = $this->client->patch($url, [
            'params' => [
                'X-Shopper-Id' => config('godaddy.shopper_id_production'),
            ],
            'json' => [
                $data
            ],
            'headers' => $this->httpHeaders
        ]);

        if ($response->getStatusCode() == 200) {
            return [
                'domain_name' => $data['name'] . '.' . $domain_name,
                'domain_register_from' => DomainManager::PROVIDER_NAME_GODADDY,
                'domain_time' => 1,
                'active' => 1,
                'id' => rand(1, 1000000)
            ];
        }

        return null;
    }

    /**
     * @param string $domain_name
     * @return mixed
     */
    public function getRecordDNS(string $domain_name)
    {
        $url = self::API_URL_PRODUCTION . self::API_VERSION . '/domains/' . $domain_name . '/records/A';
        $response = $this->client->get($url, [
            'params' => [
                'X-Shopper-Id' => config('godaddy.shopper_id_production'),
            ],
            'headers' => $this->httpHeaders
        ]);

        if ($response->getStatusCode() == 200) {
            $content = $response->getBody()->getContents();
            $output = json_decode($content);

            if (!empty($output)) {
                return $output;
            }
        }

        return null;
    }

    /**
     * @param string $domain_name
     * @param string $record_name
     * @return mixed
     */
    public function deleteRecordDNS(string $domain_name, string $record_name)
    {
        $url = self::API_URL_PRODUCTION . self::API_VERSION . '/domains/' . $domain_name . '/records/A/' . $record_name;
        $response = $this->client->delete($url, [
            'params' => [
                'X-Shopper-Id' => config('godaddy.shopper_id_production'),
            ],
            'headers' => $this->httpHeaders
        ]);

        if ($response->getStatusCode() == 200) {
            $content = $response->getBody()->getContents();
            $output = json_decode($content);

            if (!empty($output)) {
                return $output;
            }
        }

        return null;
    }

    /**
     * @param string $domain_name
     * @param string $record_name
     * @param array $data
     * @return mixed
     */
    public function updateRecordDNS(string $domain_name, string $record_name, array $data)
    {
        $url = self::API_URL_PRODUCTION . self::API_VERSION . '/domains/' . $domain_name . '/records/A/' . $record_name;

        $response = $this->client->put($url, [
            'params' => [
                'X-Shopper-Id' => config('godaddy.shopper_id_production'),
            ],
            'json' => [
                $data
            ],
            'headers' => $this->httpHeaders
        ]);

        if ($response->getStatusCode() == 200) {
            $content = $response->getBody()->getContents();
            $output = json_decode($content);

            if (!empty($output)) {
                return $output;
            }
        }

        return null;
    }
}
