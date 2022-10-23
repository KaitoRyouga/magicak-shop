<?php

namespace App\Validators;

use App\Managers\UserWebsiteManager;
use App\Managers\DomainManager;
use App\Managers\HostingManager;
use App\Repositories\UserWebsiteRepository;

class UserWebsiteValidator extends BaseValidator
{

    /**
     * @var UserWebsiteRepository
     */
    private $userWebsiteRepository;

    /**
     * @var DomainManager
     */
    private $domainManager;

    /**
     * DomainValidator constructor.
     *
     */
    public function __construct(
        UserWebsiteRepository $userWebsiteRepository,
        DomainManager $domainManager
    ) {
        $this->userWebsiteRepository = $userWebsiteRepository;
        $this->domainManager = $domainManager;
    }

    /**
     * @param array $data
     * @return array
     */
    public function validateCreateUserWebsite(array $data): array
    {
        $isFree = $this->userWebsiteRepository->checkFreeWebsiteExist();
        $checkBusinessNameExist = $this->userWebsiteRepository->checkBusinessNameExist($data);
        $domain = $this->domainManager->checkDomainNameExist([
            'domain_name' => $data['domain_name'],
            'domain_type_id' => $data['domain_type_id']
        ]);

        if (
            $isFree && ( $data['hosting_plan_id'] == HostingManager::HOSTING_PLAN_WEB_FREE ||
            $data['hosting_plan_id'] == HostingManager::HOSTING_PLAN_BUSINESS_FREE )
        )
        {
            return [
                'data' => null,
                'message' => 'You have already created a free website. Please upgrade your account.',
                'code' => 400
            ];
        }

        if (isset($checkBusinessNameExist))
        {
            return [
                'data' => null,
                'message' => 'Business name already exist.',
                'code' => 400
            ];
        }

        if (isset($domain['data']))
        {
            if (isset($domain['data']['is_available']) ) {
                if (!$domain['data']['is_available']) {
                    return [
                        'data' => null,
                        'message' => 'Domain name already exist.',
                        'code' => 400
                    ];
                }
            } else {
                foreach ($domain['data'] as $value) {
                    if ($value['domain_name'] == $data['domain_name'] && !$value['is_available']) {
                        return [
                            'data' => null,
                            'message' => 'Domain name already exist.',
                            'code' => 400
                        ];
                    }
                }
            }
        }

        if ($data['domain_type_id'] == $this->domainManager::NEW_DOMAIN && $data['total_price'] == 0)
        {
            return [
                'data' => null,
                'message' => 'You have to pay for a new domain.',
                'code' => 400
            ];
        }

        if (
            ($data['domain_type_id'] == $this->domainManager::SUB_DOMAIN ||
            $data['domain_type_id'] == $this->domainManager::OWN_DOMAIN) &&
            $data['total_price'] != 0
        )
        {
            return [
                'data' => null,
                'message' => 'You don\'t need to pay for a subdomain or own domain.',
                'code' => 400
            ];
        }

        // check domain
        if (count(explode('.', $data['domain_name'])) <= 1) {
            return [
                'data' => null,
                'message' => 'Domain name is invalid.',
                'code' => 400
            ];
        }

        return [
            'data' => $data,
            'message' => '',
            'code' => 200
        ];
    }
}
