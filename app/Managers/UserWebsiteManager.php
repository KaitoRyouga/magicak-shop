<?php

namespace App\Managers;

use App\Models\UserWebsite;
use App\Models\WebsiteMessage;
use App\Repositories\UserRepository;
use App\Repositories\UserWebsiteRepository;
use App\Repositories\WebsiteMessageRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use App\Validators\UserWebsiteValidator;
use KeycloakAdm\Facades\KeycloakAdmin;
class UserWebsiteManager extends BaseManager
{
    const AVATAR_ATTRIBUTE = 'avatar';
    const PYTHON_ROLE = 'python_system';
    const STATUS_INITIAL = 'initial';
    const STATUS_WAITING_FOR_PAYMENT = 'waiting_for_payment';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_UP = 'up';
    const STATUS_DOWN = 'down';
    const STATUS_DELETE = 'delete';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_ERROR = 'error';
    const STATUS_UPDATING_DOMAIN_IN_WEBSITE = 'updating_domain';
    const STATUSES = [
        self::STATUS_INITIAL,
        self::STATUS_WAITING_FOR_PAYMENT,
        self::STATUS_IN_PROGRESS,
        self::STATUS_UP,
        self::STATUS_DOWN,
        self::STATUS_DELETE,
        self::STATUS_SUSPENDED,
        self::STATUS_ERROR,
        self::STATUS_UPDATING_DOMAIN_IN_WEBSITE
    ];

    const CURRENT_TASK_CREATE_WEBSITE = 'create_web';
    const CURRENT_TASK_DELETE_WEBSITE = 'delete_web';
    const CURRENT_TASK_UPDATE_DOMAIN = 'update_domain';
    const CURRENT_TASK_UPGRADE_PLAN = 'upgrade_plan';
    const CURRENT_TASK_UP_DNS = 'update_dns';
    const CURRENT_TASKS = [
        self::CURRENT_TASK_CREATE_WEBSITE,
        self::CURRENT_TASK_UP_DNS,
        self::CURRENT_TASK_DELETE_WEBSITE,
        self::CURRENT_TASK_UPDATE_DOMAIN,
        self::CURRENT_TASK_UPGRADE_PLAN
    ];

    const FREE_WEBSITE = 1;
    const PREMIUM_WEBSITE = 2;

    const SOCKET_CHANGE_STATUS = 'status';
    const SOCKET_CHANGE_WEBSITE_URL = 'website_url';
    const SOCKET_CHANGE_WEBSITE_MESSAGE = 'website_message';
    const SOCKET_CHANGE_HOSTING_EXPIRED_DATE = 'hosting_expired_date';

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var UserWebsiteRepository
     */
    protected $userWebsiteRepository;

    /**
     * @var WebsiteMessageRepository
     */
    protected $websiteMessageRepository;

    /**
     * @var TemplateManager
     */
    protected $templateManager;

    /**
     * @var DomainManager
     */
    protected $domainManager;

    /**
     * @var HostingManager
     */
    protected $hostingManager;

    /**
     * @var DataCenterLocationManager
     */
    protected $dataCenterLocationManager;

    /**
     * @var TemporaryDomainManager
     */
    protected $temporaryDomainManager;

    /**
     * @var SocketManager
     */
    protected $socketManager;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var SettingManager
     */
    protected $settingManager;

    /**
     * @var SystemDomainManager
     */
    protected $systemDomainManager;

    /**
     * @var UserWebsiteValidator
     */
    protected $userWebsiteValidator;

    /**
     * UserWebsiteManager constructor.
     * @param UserRepository $userRepository
     * @param UserWebsiteRepository $userWebsiteRepository
     * @param WebsiteMessageRepository $websiteMessageRepository
     * @param TemplateManager $templateManager
     * @param DomainManager $domainManager
     * @param HostingManager $hostingManager
     * @param DataCenterLocationManager $dataCenterLocationManager
     * @param TemporaryDomainManager $temporaryDomainManager
     * @param SocketManager $socketManager
     * @param UserManager $userManager
     * @param UserWebsiteValidator $userWebsiteValidator
     * @param SettingManager $settingManager,
     * @param SystemDomainManager $systemDomainManager
     */
    public function __construct(
        UserRepository $userRepository,
        UserWebsiteRepository $userWebsiteRepository,
        TemplateManager $templateManager,
        DomainManager $domainManager,
        HostingManager $hostingManager,
        DataCenterLocationManager $dataCenterLocationManager,
        WebsiteMessageRepository $websiteMessageRepository,
        TemporaryDomainManager $temporaryDomainManager,
        SocketManager $socketManager,
        UserManager $userManager,
        UserWebsiteValidator $userWebsiteValidator,
        SettingManager $settingManager,
        SystemDomainManager $systemDomainManager
    ) {
        $this->userRepository = $userRepository;
        $this->userWebsiteRepository = $userWebsiteRepository;
        $this->templateManager = $templateManager;
        $this->domainManager = $domainManager;
        $this->hostingManager = $hostingManager;
        $this->dataCenterLocationManager = $dataCenterLocationManager;
        $this->websiteMessageRepository = $websiteMessageRepository;
        $this->temporaryDomainManager = $temporaryDomainManager;
        $this->socketManager = $socketManager;
        $this->userManager = $userManager;
        $this->userWebsiteValidator = $userWebsiteValidator;
        $this->settingManager = $settingManager;
        $this->systemDomainManager = $systemDomainManager;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getUserWebsites(): LengthAwarePaginator
    {
        return $this->userWebsiteRepository->getUserWebsites();
    }

    /**
     * @return bool
     */
    public function checkUserWebsiteFree(): bool
    {
        return $this->userWebsiteRepository->checkFreeWebsiteExist();
    }

    /**
     * @param array $data
     * @return array
     */
    public function chooseDomain(array $data): array
    {
        $userWebsite = $this->userWebsiteRepository->getUserWebsiteById($data['user_website_id'], false);

        if ($data['is_temporary']) {
            // copy temporary domain to domain table
            $domain = $this->temporaryDomainManager->getDomainByName($data['domain_name']);
            $dataCreateDomain = [
                'domain_name' => $data['domain_name'],
                'domain_time' => '',
                'domain_type_id' => 2
            ];
            $newId = $this->domainManager->updateOrCreateDomain($dataCreateDomain)->id;
        } else {
            $domain = $this->domainManager->getDomainByName($data['domain_name']);
            $newId = $domain->id;
        }

        if ($userWebsite->domain_id != $newId) {
            $userWebsite->domain_id = $newId;
            if ($data['is_temporary']) {
                $userWebsite->status = self::STATUS_UPDATING_DOMAIN_IN_WEBSITE;
                $userWebsite->is_temporary_domain = 1;
            }
            $userWebsite->save();
        }

        return [
            'data' => [
                'domain_name' => $domain->domain_name
            ],
            'message' => 'Domain has been chosen',
            'code' => 200
        ];
    }

    /**
     * @param int $id
     * @return UserWebsite|null
     */
    public function getUserWebsiteById(int $id): ?UserWebsite
    {
        // check python token
        $roles = $this->userManager->getKeyCloakUserRoles();
        $isGetAll = in_array(self::PYTHON_ROLE, $roles);

        return $this->userWebsiteRepository->getUserWebsiteById($id, $isGetAll);
    }

    /**
     * @param array $data
     */
    public function deleteByUserIdAndUserWebsiteId(array $data): void
    {
        $this->userWebsiteRepository->deleteByUserIdAndUserWebsiteId($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function deleteUserWebsiteById(array $data): array
    {
        // get user website
        $userWebsite = $this->userWebsiteRepository->getUserWebsiteById($data['user_website_id'], false);

        // status error, delete by laravel
        if (isset($userWebsite) && $userWebsite->status == self::STATUS_ERROR) {

            // delete record
            if ($userWebsite->domain->domain_type_id == DomainManager::SUB_DOMAIN) {

                $systemDomain = $this->systemDomainManager->getSystemDomainByName([
                    'domain_name' => explode('.', $userWebsite->domain->domain_name)[1] . '.' . explode('.', $userWebsite->domain->domain_name)[2]
                ]);

                $this->domainManager->deleteRecordDNS(
                    $systemDomain->remote_domain_id,
                    $userWebsite->domain->remote_domain_id
                );
            }

            $this->userWebsiteRepository->deleteUserWebsite([
                'user_id' => auth()->id(),
                'user_website_id' => $userWebsite->id
            ]);

            return [
                'data' => $userWebsite,
                'message' => 'Website has been deleted',
                'code' => 200
            ];
        }

        // status updating, delete by python
        if (isset($userWebsite) && $userWebsite->status == self::STATUS_UPDATING_DOMAIN_IN_WEBSITE) {

            // delete record
            if ($userWebsite->domain->domain_type_id == DomainManager::SUB_DOMAIN) {

                $systemDomain = $this->systemDomainManager->getSystemDomainByName([
                    'domain_name' => explode('.', $userWebsite->domain->domain_name)[1] . '.' . explode('.', $userWebsite->domain->domain_name)[2]
                ]);

                $this->domainManager->deleteRecordDNS(
                    $systemDomain->remote_domain_id,
                    $userWebsite->domain->remote_domain_id
                );
            }

            $this->userWebsiteRepository->updateCurrentTaskUserWebsite(
                array_merge($data, [
                    'current_tasks' => self::CURRENT_TASK_DELETE_WEBSITE
                ])
            );

            $userWebsite = $this->userWebsiteRepository->getUserWebsiteById($data['user_website_id'], false);

            // push notification delete website
            $this->pushDeleteMessageData($userWebsite);

            return [
                'data' => $userWebsite,
                'message' => 'Website has been deleted',
                'code' => 200
            ];
        }

        // TODO: fix this if production
        if (isset($userWebsite) && $userWebsite->status !== self::STATUS_UP && $userWebsite->status !== self::STATUS_ERROR && $userWebsite->status !== self::STATUS_UPDATING_DOMAIN_IN_WEBSITE) {
            return [
                'data' => null,
                'code' => 400,
                'message' => 'Website is not up, you can not delete it'
            ];
        }

        if (isset($userWebsite) && $userWebsite->status == self::STATUS_UP) {

            $this->userWebsiteRepository->updateCurrentTaskUserWebsite(
                array_merge($data, [
                    'current_tasks' => self::CURRENT_TASK_DELETE_WEBSITE
                ])
            );

            $userWebsite = $this->userWebsiteRepository->getUserWebsiteById($data['user_website_id'], false);

            // if (isset($userWebsite) && $userWebsite->status == self::STATUS_UP) {
            if (isset($userWebsite)) {

                // delete record
                if ($userWebsite->domain->domain_type_id == DomainManager::SUB_DOMAIN) {

                    $systemDomain = $this->systemDomainManager->getSystemDomainByName([
                        'domain_name' => explode('.', $userWebsite->domain->domain_name)[1] . '.' . explode('.', $userWebsite->domain->domain_name)[2]
                    ]);

                    $this->domainManager->deleteRecordDNS(
                        $systemDomain->remote_domain_id,
                        $userWebsite->domain->remote_domain_id
                    );
                }

                // TODO NEED: delete group website in keycloak

                // push notification delete website
                $this->pushDeleteMessageData($userWebsite);

                // delete user website
                return [
                    'data' => $userWebsite,
                    'message' => 'Website has been deleted',
                    'code' => 200
                ];
            }
        }

        return [
            'message' => 'User website not found',
            'code' => 404,
            'data' => null
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function createUserWebsite(array $data): array
    {
        // check free
        $validate = $this->userWebsiteValidator->validateCreateUserWebsite($data);
        if ($validate['code'] != 200) {
            return $validate;
        }

        // set hosting data
        $hostingPlan = Cache::remember('hosting_plan_' . $data['hosting_plan_id'], Carbon::now()->addMinutes(1), function () use ($data) {
            return $this->hostingManager->getHostingPlanById($data['hosting_plan_id']);
        });

        if (!isset($hostingPlan)) {
            return [
                'data' => null,
                'message' => 'Hosting plan not found.',
                'code' => 404
            ];
        }

        $data['hosting_ram'] = $hostingPlan->ram;
        $data['hosting_cpu'] = $hostingPlan->cpu;
        $data['backup_option'] = $hostingPlan->backup;
        $data['hosting_ip'] = $hostingPlan->cluster->where('dc_location_id', $data['dc_location_id'])->first()->systemDomain->ip;
        $data['hosting_app_storage'] = $hostingPlan->hosting_app_storage;
        $data['hosting_db_storage'] = $hostingPlan->hosting_db_storage;
        $data['hosting_cluster'] = $hostingPlan->cluster->where('dc_location_id', $data['dc_location_id'])->first()->name;

        // set template data
        $template = $this->templateManager->getTemplateById($data['template_id']);

        if (!isset($template)) {
            return [
                'data' => null,
                'message' => 'Template not found.',
                'code' => 404
            ];
        }

        $data['template_name'] = $template->name;
        $data['template_code'] = $template->code;
        $data['template_version'] = $template->version;

        // set data center location data
        $dcLocation = $this->dataCenterLocationManager->getDcLocationById($data['dc_location_id']);

        if (!isset($dcLocation)) {
            return [
                'data' => null,
                'message' => 'Data center location not found.',
                'code' => 404
            ];
        }

        $data['dc_location'] = $dcLocation->location;

        $data['domain_id'] = $this->domainManager->updateOrCreateDomain($data)->id;

        $data['status'] = self::STATUS_WAITING_FOR_PAYMENT;
        $data['current_tasks'] = self::CURRENT_TASK_CREATE_WEBSITE;

        unset($data['domain_name']);
        unset($data['domain_auth_key']);
        unset($data['is_transfer']);
        unset($data['domain_time']);

        $userWebsite = $this->userWebsiteRepository->updateOrCreate(null, $data);

        if (
            $data['domain_type_id'] == DomainManager::SUB_DOMAIN &&
            $data['total_price'] == 0 &&
            ( $data['hosting_plan_id'] == 1 ||
            $data['hosting_plan_id'] == 5 )
        )
        {
            $userWebsite->status = self::STATUS_INITIAL;
            $userWebsite->save();
        }

        return [
            'data' => $userWebsite,
            'message' => 'Website created successfully.',
            'code' => 200
        ];
    }

    /**
     * @param int $id
     * @param array $data
     * @return UserWebsite|null
     */
    public function initUserWebsite(int $id, array $data): ?UserWebsite
    {
        // check python token
        $roles = $this->userManager->getKeyCloakUserRoles();

        if (!in_array(self::PYTHON_ROLE, $roles)) {
            return abort(401);
        }

        // get user website
        $userWebsite = $this->userWebsiteRepository->getUserWebsiteById($id, true);
        if ($userWebsite) {

            if (isset($data['status']) && $userWebsite->status != $data['status']) {
                $this->socketManager->pushToMessageSocket([
                    "message" => "Website status has been changed",
                    "data" => array_merge(
                        [
                            'user_website_id' => $id,
                            'channel' => $userWebsite->user->keycloak_userId
                        ],
                        $data
                    ),
                    "type" => self::SOCKET_CHANGE_STATUS
                ]);
            }

            if (isset($data['hosting_expired_date']) && $userWebsite->hosting_expired_date != $data['hosting_expired_date']) {
                $this->socketManager->pushToMessageSocket([
                    "message" => "Website hosting expired date has been changed",
                    "data" => array_merge(
                        [
                            'user_website_id' => $id,
                            'channel' => $userWebsite->user->keycloak_userId
                        ],
                        $data
                    ),
                    "type" => self::SOCKET_CHANGE_HOSTING_EXPIRED_DATE
                ]);
            }

            if (isset($data['website_url']) && $userWebsite->website_url != $data['website_url']) {
                $this->socketManager->pushToMessageSocket([
                    "message" => "Website url has been changed",
                    "data" => array_merge(
                        [
                            'user_website_id' => $id,
                            'channel' => $userWebsite->user->keycloak_userId
                        ],
                        $data
                    ),
                    "type" => self::SOCKET_CHANGE_WEBSITE_URL
                ]);
            }

            if (isset($data['system_message'])) {
                $this->updateWebsiteMessage([
                    'user_website_id' => $id,
                    'message' => $data['system_message'],
                    'active' => self::STATUS_ACTIVE,
                    'created_id' => Auth::id()
                ]);
            }
        }

        unset($data['system_message']);

        return $this->userWebsiteRepository->updateOrCreate($id, $data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function upgradePlan(array $data): array
    {
        $id = $data['user_website_id'];
        $oldUserWebsite = $this->userWebsiteRepository->getUserWebsiteById($id, false);
        $hosting_plan_id = $oldUserWebsite->hosting_plan_id;


        if ($oldUserWebsite->status !== self::STATUS_UP) {
            return [
                'data' => null,
                'code' => 400,
                'message' => 'Website is not up, you can not upgrade plan.'
            ];
        }

        unset($data['user_website_id']);

        $hostingPlan = $this->hostingManager->getHostingPlanById($data['hosting_plan_id']);

        if (!isset($hostingPlan)) {
            return [
                'data' => null,
                'code' => 404,
                'message' => 'Hosting plan not found.'
            ];
        }

        $data['hosting_app_storage'] = $hostingPlan->hosting_app_storage;
        $data['hosting_db_storage'] = $hostingPlan->hosting_db_storage;
        $data['total_price'] = $hostingPlan->prices()->first()->price * $data["latest_purchased_package"];

        $userWebsite = $this->userWebsiteRepository->updateOrCreate($id, $data);

        if (isset($userWebsite) && $userWebsite->hosting_plan_id != $hosting_plan_id) {
            $this->userWebsiteRepository->updateCurrentTaskUserWebsite(array_merge(
                [
                    'current_tasks' => self::CURRENT_TASK_UPGRADE_PLAN,
                    'user_website_id' => $userWebsite->id,
                    'created_id' => Auth::id()
                ],
                $data
            ));

            $userWebsite = $this->userWebsiteRepository->getUserWebsiteById($id, false);

            if ($userWebsite->current_tasks == self::CURRENT_TASK_UPGRADE_PLAN) {
                $this->pushUpgradePlanMessageData($userWebsite);
            }
        }

        $this->socketManager->pushToMessageSocket([
            "message" => "Website updated successfully.",
            "data" => null,
            "type" => "upgrade_plan"
        ]);

        return [
            'data' => $userWebsite,
            'code' => 200,
            'message' => 'Website updated successfully.'
        ];
    }

    /**
     * @param array $data
     * @return UserWebsite|null
     */
    public function checkBusinessNameExist(array $data): ?UserWebsite
    {
        return $this->userWebsiteRepository->checkBusinessNameExist($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function updateDomainUserWebsite(array $data): array
    {
        $userWebsite = $this->userWebsiteRepository->getUserWebsiteById($data['user_website_id'], false);
        $domain = $this->domainManager->getDomainByName($data['domain_name']);

        if (!isset($domain)) {
            return [
                'data' => null,
                'code' => 400,
                'message' => 'Domain not found.'
            ];
        }

        if ($domain->domain_type_id == DomainManager::SUB_DOMAIN) {
            return [
                'data' => null,
                'code' => 400,
                'message' => 'Domain is sub domain, you can not update domain'
            ];
        }

        if ($userWebsite->status !== self::STATUS_UP) {
            return [
                'data' => null,
                'code' => 400,
                'message' => 'Website is not up, you can not update domain'
            ];
        }

        if($domain->userWebsite()->exists()) {
            return [
                'data' => null,
                'code' => 400,
                'message' => 'Domain name is invalid'
            ];
        }

        $systemDomain = $userWebsite->hostingPlan->cluster->where('dc_location_id', $userWebsite->dc_location_id)->first()->systemDomain;
        $ip = $systemDomain ? $systemDomain->ip : config('magicak.ip');

        // Check domain exist in vodien, check ip and update ip point
        if (isset($domain) && isset($systemDomain)) {

            $this->domainManager->updateDomainForUserWebsite($domain, $systemDomain);
        }

        // if domain not related to user website, point domain to user website
        if(!$domain->userWebsite()->exists()) {

            // check domain online
            $ipHostName = gethostbyname($domain->domain_name);

            if ($ip == $ipHostName) {
                $userWebsite->domain_id = $domain->id;
                $userWebsite->domain_type_id = $domain->domain_type_id;
                $userWebsite->current_tasks = self::CURRENT_TASK_UP_DNS;
                $userWebsite->status = self::STATUS_UPDATING_DOMAIN_IN_WEBSITE;
                $userWebsite->save();
                $this->pushUpdateDomainMessageData($userWebsite);
            } else {
                $userWebsite->domain_id = $domain->id;
                $userWebsite->domain_type_id = $domain->domain_type_id;
                $userWebsite->current_tasks = self::CURRENT_TASK_UP_DNS;
                $userWebsite->status = self::STATUS_UPDATING_DOMAIN_IN_WEBSITE;
                $userWebsite->save();

                $this->socketManager->pushToMessageSocket([
                    "message" => "Website updated successfully.",
                    "data" => array_merge(
                        [
                            'user_website_id' => $userWebsite->id,
                            'channel' => $userWebsite->user->keycloak_userId
                        ],
                        [
                            'status' => $userWebsite->status
                        ]
                    ),
                    "type" => UserWebsiteManager::SOCKET_CHANGE_STATUS
                ]);
            }
        }

        return [
            'data' => $userWebsite,
            'code' => 200,
            'message' => 'Update domain success'
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function triggerInitUserWebsite(array $data): array
    {
        try {
            $userWebsite = $this->checkBusinessNameExist($data);

            if (isset($userWebsite) && $userWebsite->status == self::STATUS_INITIAL && $userWebsite->current_tasks == self::CURRENT_TASK_CREATE_WEBSITE) {

                if(!isset($userWebsite->user->group_id) || $userWebsite->user->group_id == NULL || empty($userWebsite->user->group_id)) {
                    
                    $newGroup = KeycloakAdmin::group()->create([
                        'body' => [
                            'name' => $userWebsite->user->keycloak_username,
                        ]
                    ]);

                    KeycloakAdmin::user()->addToGroup([
                        'id' => $userWebsite->user->keycloak_userId,
                        'groupId' => $newGroup['id']
                    ]);

                    $userWebsite->user->group_id = $newGroup['id'];
                    $userWebsite->user->save();
                }

                // update user website if free plan
                if ($userWebsite->total_price == 0) {
                    $userWebsite->status = self::STATUS_INITIAL;
                    $userWebsite->save();
                }
                // setup keycloak
                $groupId = $this->setupUserWebsiteKeycloak($userWebsite);

                // get temporary domain
                $temporaryDomain = $this->temporaryDomainManager->getTemporaryDomainAvailable([
                    'dc_location_id' => $userWebsite->dc_location_id,
                ]);
                $this->temporaryDomainManager->changeAvailable($temporaryDomain->id, ['available' => 0]);
                $userWebsite->temporary_domain_name = $temporaryDomain->domain_name;

                $this->pushPythonMessageData($userWebsite, $groupId);

                // register domain
                // TODO: remove if production
                $domainProvider = $this->settingManager->getProviderDomain() . ( config('app.env') == 'production' ? '_production' : '_sandbox');

                if ($userWebsite->total_price == 0 || $domainProvider == DomainManager::PROVIDER_NAME_DREAMSCAPE_SANDBOX || $domainProvider == DomainManager::PROVIDER_NAME_GODADDY_SANDBOX) {
                    $result = $this->domainManager->registerDomain(
                        DomainManager::CREATE_DOMAIN_IN_USER_WEBSITE,
                        $userWebsite->domain,
                        $userWebsite->hostingPlan->cluster->where('dc_location_id', $userWebsite->dc_location_id)->first()->systemDomain
                    );

                    if(!$result['status']) {
                        return [
                            'data' => null,
                            'code' => 400,
                            'message' => $result['message']
                        ];
                    }
                }

                // create website message
                $this->createWebsiteMessage($userWebsite);

                return [
                    'data' => $userWebsite,
                    'message' => 'Website created successfully.',
                    'code' => 200
                ];
            }

            return [
                'data' => null,
                'message' => 'Website not created.',
                'code' => 400
            ];

        } catch (\Throwable $e) {
            return [
                'data' => null,
                'code' => 500,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * @param UserWebsite $userWebsite
     * @return ?WebsiteMessage
     */
    public function createWebsiteMessage(UserWebsite $userWebsite): ?WebsiteMessage
    {
        $data = [
            'created_id' => Auth::id(),
            'user_website_id' => $userWebsite->id,
            'message' => 'Website ' . $userWebsite->business_name . ' is created',
            'active' => 1
        ];

        return $this->websiteMessageRepository->createWebsiteMessage($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function updateWebsiteMessage(array $data): array
    {
        $result = $this->websiteMessageRepository->createWebsiteMessage($data);
        $messsage = $this->websiteMessageRepository->getWebsiteMessageByUserWebsiteId($data['user_website_id']);

        $this->socketManager->pushToMessageSocket([
            "message" => "Website message updated successfully.",
            "data" => array_merge(['user_website_id' => $data['user_website_id']], $messsage->toArray()),
            "type" => UserWebsiteManager::SOCKET_CHANGE_WEBSITE_MESSAGE
        ]);

        return [
            'data' => $result,
            'code' => 200,
            'message' => 'Update website message success'
        ];
    }

    /**
     * @param UserWebsite $userWebsite
     * @return mixed
     *
     */
    public function setupUserWebsiteKeycloak(UserWebsite $userWebsite)
    {
        $user = $userWebsite->user;

        // create new role
        // try {
        //     KeycloakAdmin::clientRole()->create([
        //         'id' => config('keycloakAdmin.client.id_client'),
        //         'body' => [
        //             'name' => $userWebsite->business_name . '_' . $userWebsite->user->keycloak_username . '_role',
        //             'composite' => false,
        //             'clientRole' => true,
        //             'containerId' => config('keycloakAdmin.client.id_client')
        //         ],
        //     ]);
        // } catch (\Throwable $e) {
        //     // dd($e->getMessage());
        //     // don't throw an error
        // }

        // get role by name
        // $newRole = KeycloakAdmin::clientRole()->getByName([
        //     'id' => config('keycloakAdmin.client.id_client'),
        //     'role' => $userWebsite->business_name . '_' . $userWebsite->user->keycloak_username . '_role',
        // ]);

        $newGroup = KeycloakAdmin::group()->children([
            // 'id' => "26c2fc79-0617-46bc-8e2c-529fe6758a6f",
            'id' => $userWebsite->user->group_id,
            'body' => [
                'name' => $userWebsite->id
            ],
        ]);

        // KeycloakAdmin::group()->roleMapping([
        //     'id' => $newGroup['id'],
        //     'clientId' => config('keycloakAdmin.client.id_client'),
        //     'body' => [[
        //         'id' => $newRole['id'],
        //         'name' => $newRole['name'],
        //         'composite' => $newRole['composite'],
        //         'clientRole' => $newRole['clientRole'],
        //         'containerId' => $newRole['containerId'],
        //     ]],
        // ]);

        // add user to group
        KeycloakAdmin::user()->addToGroup([
            'id' => $user->keycloak_userId,
            'groupId' => $newGroup['id']
        ]);

        return $newGroup['id'];
    }

    /**
     * @param UserWebsite $userWebsite
     * @param String $groupId
     */
    public function pushPythonMessageData(UserWebsite $userWebsite, String $groupId): void
    {
        $user = $this->userManager->getUserById($userWebsite->created_id);

        $data = [
            'website_id' => $userWebsite->id,
            'keycloak_user_id' => $user->keycloak_userId,
            'keycloak_username' => $user->keycloak_username,
            'business_name' => $userWebsite->business_name,
            'current_tasks' => $userWebsite->current_tasks,
            'status' => $userWebsite->status,
            'web_template_code' => $userWebsite->template ? $userWebsite->template->code : null,
            'template_app_storage' => $userWebsite->template ? $userWebsite->template->template_app_storage : null,
            'template_db_storage' => $userWebsite->template ? $userWebsite->template->template_db_storage : null,
            'hosting_plan' => $userWebsite->hostingPlan ? $userWebsite->hostingPlan->name : null,
            'hosting_cpu' => $userWebsite->hosting_cpu,
            'hosting_ram' => $userWebsite->hosting_ram,
            'hosting_app_storage' => $userWebsite->hosting_app_storage,
            'hosting_db_storage' => $userWebsite->hosting_db_storage,
            'latest_purchased_package' => $userWebsite->latest_purchased_package,
            'dc_location' => $userWebsite->dcLocation->location,
            'hosting_ip' => $userWebsite->hosting_ip,
            'domain_type' => $userWebsite->domainType->name,
            'domain_name' => $userWebsite->domain->domain_name,
            'temporary_domain_name' => $userWebsite->temporary_domain_name,
            'group_id' => $groupId
        ];

        Queue::pushRaw(json_encode($data));
    }

    /**
     * @param UserWebsite $userWebsite
     */
    public function pushDeleteMessageData(UserWebsite $userWebsite): void
    {
        $data = [
            'website_id' => $userWebsite->id,
            'business_name' => $userWebsite->business_name,
            'current_tasks' => $userWebsite->current_tasks,
            'status' => $userWebsite->status,
            'dc_location' => $userWebsite->dcLocation->location,
            'hosting_ip' => $userWebsite->hosting_ip
        ];

        Queue::pushRaw(json_encode($data));
    }

    /**
     * @param UserWebsite $userWebsite
     */
    public function pushUpdateDomainMessageData(UserWebsite $userWebsite): void
    {
        $user = $this->userManager->getUserById($userWebsite->created_id);

        $data = [
            'website_id' => $userWebsite->id,
            'keycloak_user_id' => $user->keycloak_userId,
            'keycloak_username' => $user->keycloak_username,
            'business_name' => $userWebsite->business_name,
            'current_tasks' => $userWebsite->current_tasks,
            'status' => $userWebsite->status,
            'web_template_code' => $userWebsite->template ? $userWebsite->template->code : null,
            'dc_location' => $userWebsite->dcLocation->location,
            'hosting_ip' => $userWebsite->hosting_ip,
            'domain_type' => $userWebsite->domainType->name,
            'domain_name' => $userWebsite->domain->domain_name
        ];

        Queue::pushRaw(json_encode($data));
    }

    /**
     * @param UserWebsite $userWebsite
     * @param string $status
     */
    public function pushUpgradePlanMessageData(UserWebsite $userWebsite): void
    {
        $user = $this->userManager->getUserById($userWebsite->created_id);

        $data = [
            'website_id' => $userWebsite->id,
            'keycloak_user_id' => $user->keycloak_userId,
            'keycloak_username' => $user->keycloak_username,
            'business_name' => $userWebsite->business_name,
            'current_tasks' => $userWebsite->current_tasks,
            'status' => $userWebsite->status,
            'web_template_code' => $userWebsite->template ? $userWebsite->template->code : null,
            'template_app_storage' => $userWebsite->template ? $userWebsite->template->template_app_storage : null,
            'template_db_storage' => $userWebsite->template ? $userWebsite->template->template_db_storage : null,
            'hosting_plan' => $userWebsite->hostingPlan ? $userWebsite->hostingPlan->name : null,
            'hosting_cpu' => $userWebsite->hosting_cpu,
            'hosting_ram' => $userWebsite->hosting_ram,
            'hosting_app_storage' => $userWebsite->hosting_app_storage,
            'hosting_db_storage' => $userWebsite->hosting_db_storage,
            'latest_purchased_package' => $userWebsite->latest_purchased_package,
            'dc_location' => $userWebsite->dcLocation->location,
            'hosting_ip' => $userWebsite->hosting_ip,
            'domain_type' => $userWebsite->domainType->name,
            'domain_name' => $userWebsite->domain->domain_name
        ];

        Queue::pushRaw(json_encode($data));
    }

    /**
     * @return array
     */
    public function getUserWebsiteUpdatingDomain(): array
    {
        $result = $this->userWebsiteRepository->getUserWebsiteUpdatingDomain();
        $data = [];

        foreach ($result as $value) {
            $data = [
                'website_id' => $value->id,
                'keycloak_user_id' => $value->user->keycloak_userId,
                'keycloak_username' => $value->user->keycloak_username,
                'business_name' => $value->business_name,
                'current_tasks' => $value->current_tasks,
                'status' => $value->status,
                'web_template_code' => $value->template ? $value->template->code : null,
                'hosting_ip' => $value->hosting_ip,
                'domain_name' => $value->domain->domain_name,
            ];

            Queue::pushRaw(json_encode($data));
        }

        return [
            'data' => $data,
            'message' => 'Successfully get user website with status "updating_domain"',
            'code' => 200
        ];
    }
}
