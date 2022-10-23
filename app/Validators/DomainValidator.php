<?php

namespace App\Validators;

use App\Managers\DomainManager;

class DomainValidator extends BaseValidator
{
    /**
     * DomainValidator constructor.
     *
     */
    public function __construct() {}

    /**
     * @param array $data
     * @return array
     */
    public function validateCheckDomainNameExist(array $data): array
    {
        if (!isset($data['domain_name'])) {
            return [
                'status' => false,
                'message' => 'Domain name is required.'
            ];
        }

        if (!isset($data['domain_type_id'])) {
            return [
                'status' => false,
                'message' => 'Domain type is required.'
            ];
        }

        $domainName = $data['domain_name'];
        $domainTypeId = $data['domain_type_id'];

        if (empty($domainName)) {
            return [
                'status' => false,
                'message' => 'Domain name is required.'
            ];
        }

        if (empty($domainTypeId)) {
            return [
                'status' => false,
                'message' => 'Domain type is required.'
            ];
        }

        if(
            $domainTypeId != DomainManager::NEW_DOMAIN &&
            $domainTypeId != DomainManager::SUB_DOMAIN &&
            $domainTypeId != DomainManager::OWN_DOMAIN
        ) {
            return [
                'status' => false,
                'message' => 'Domain type is invalid.'
            ];
        }

        if ($domainTypeId == DomainManager::NEW_DOMAIN) {
            if (!isset(explode('.', $domainName)[0]) || !isset(explode('.', $domainName)[1]) || isset(explode('.', $domainName)[2])) {
                return [
                    'status' => false,
                    'message' => 'Domain name is invalid.'
                ];
            }
        }

        if ($domainTypeId == DomainManager::SUB_DOMAIN) {
            if (!isset(explode('.', $domainName)[0]) || !isset(explode('.', $domainName)[1]) || !isset(explode('.', $domainName)[2])) {
                return [
                    'status' => false,
                    'message' => 'Domain name is invalid.'
                ];
            }
        }

        return [
            'status' => true,
            'message' => 'Domain name is valid.'
        ];
    }
}
