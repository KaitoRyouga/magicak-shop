<?php

namespace App\Managers;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserAttributeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use KeycloakAdm\Facades\KeycloakAdmin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;

class UserManager extends BaseManager
{
    const AVATAR_ATTRIBUTE = 'avatar';
    const API_URL_KEYCLOAK = 'https://auth.magicak.com/auth/realms/';
    const KEYCLOAK_REALM = 'local-dev';
    const API_KEYCLOAK_PREFIX = '/protocol/openid-connect/token';
    const KEYCLOAK_CLIENT_ID = 'local-vue-auth';
    const KEYCLOAK_REDIRECT_URI = 'http://localhost:8081';

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var UserAttributeRepository
     */
    protected $userAttributeRepository;

    /**
     * @var Client
     */
    protected $client;

    /**
     * UserManager constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository,
        UserAttributeRepository $userAttributeRepository,
        Client $client
    ) {
        $this->userRepository = $userRepository;
        $this->userAttributeRepository = $userAttributeRepository;
        $this->client = $client;
    }

    /**
     * @param array $data
     * @return array
     */
    public function addToGroup(array $data)
    {
        try {
            $user = KeycloakAdmin::user()->getUser([
                'username' => $data['username']
            ]);

            $result = KeycloakAdmin::user()->addToGroup([
                'id' => $user[0]['id'],
                'groupId' => $data['group_id']
            ]);

            return [
                'code' => 200,
                'data' => null,
                'message' => 'User added to group successfully'
            ];
        } catch (\Throwable $th) {

            return [
                'code' => 500,
                'data' => null,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * @return array|null
     */
    public function getKeyCloakUser(): ?array
    {
        $user = null;

        if (!empty(Auth::token())) {
            $token = json_decode(Auth::token(), true);
            $keyCloakUserId = $token['sub'];
            $user = KeycloakAdmin::user()->get(['id' => $keyCloakUserId]);
            $user['roles'] = KeycloakAdmin::user()->getRealmRoles(['id' => $keyCloakUserId]);
            $user['sessions'] = KeycloakAdmin::user()->sessions(['id' => $keyCloakUserId]);
        }

        return $user;
    }

    /**
     * @return array|null
     */
    public function getKeyCloakUserRoles(): ?array
    {
        // check python token
        $user = Cache::remember('user_' . Auth::token(), Carbon::now()->addMinutes(15), function () {
            return $this->getKeyCloakUser();
        });
        return array_column($user['roles'], "name");
    }

    /**
     * @return array|null
     */
    public function getProfile(): ?array
    {
        $result = null;
        if ($user = $this->getKeyCloakUser()) {

            // get user attribute
            $attributes = $this->userAttributeRepository->getAllAttributes();

            $result = [
                'username' => $user['username'],
                'emailVerified' => $user['emailVerified'],
                'active' => $user['enabled'],
                'firstName' => $user['firstName'],
                'lastName' => $user['lastName'],
                'email' => $user['email'],
                'roles' => array_column($user['roles'], "name"),
            ];

            // append user attributes
            foreach ($attributes as $attribute) {
                $result[$attribute->mapping_field] = empty($user['attributes']) ? null
                    : (empty($user['attributes'][$attribute->mapping_field]) ? null
                        : $user['attributes'][$attribute->mapping_field]);

                // handle for avatar
                if ($attribute->mapping_field == self::AVATAR_ATTRIBUTE && !empty($result[$attribute->mapping_field][0])) {
                    $result[$attribute->mapping_field][0] = Storage::url($result[$attribute->mapping_field][0]);
                }
            }
        }

        return $result;
    }

    /**
     * @param string $username
     * return mixed
     */
    public function checkUsernameExist(string $username)
    {
        return $this->userRepository->checkUsernameExist($username);
    }

    /**
     * @param string $userId
     * @param string $username
     * @return User
     */
    public function createUser(string $userId, string $username): User
    {
        return $this->userRepository->createUser($userId, $username);
    }

    /**
     * @param array $data
     */
    public function updatePassword(array $data): void
    {
        $id = $this->getKeyCloakUser()['id'];

        KeycloakAdmin::user()->setTemporaryPassword([
            'id' => $id,
            'body' => [
                "value" => $data['password'],
            ],
        ]);
    }

    /**
     * @param array $data
     * @return array|null
     */
    public function updateUser(array $data): ?array
    {
        $user = $this->getKeyCloakUser();

        $userAttribute = $this->userAttributeRepository->getAllAttributes();

        foreach ($userAttribute as $value) {
            if (in_array($value->mapping_field, array_keys($data))) {
                $data['attributes'][$value->mapping_field] = $data[$value->mapping_field];
                unset($data[$value->mapping_field]);
            } else {
                $data['attributes'][$value->mapping_field] = $value->name;
            }

            if ($value->mapping_field == self::AVATAR_ATTRIBUTE && isset($user['attributes']['avatar'][0])){
                $data['attributes'][$value->mapping_field] = $user['attributes']['avatar'][0];
            }
        }

        $this->userRepository->updateUser($data, $user['id']);

        return $this->getProfile();
    }

    /**
     * @param array $data
     * @return array|null
     */
    public function uploadAvatar(array $data): ?array
    {
        $user = $this->getKeyCloakUser();

        if (isset($user['attributes'])) {
            // remove old avatar
            if (!empty($user['attributes'][self::AVATAR_ATTRIBUTE])) {
                Storage::delete($user['attributes'][self::AVATAR_ATTRIBUTE]);
            }

            foreach ($user['attributes'] as $key => $value) {
                if ($key !== self::AVATAR_ATTRIBUTE) {
                    $data['attributes'][$key] = $value[0];
                }
            }
        }

        $this->userRepository->updateUser($data, $user['id']);
        return $this->getProfile();
    }

    /**
     * @param int $id
     * @return User
     */
    public function getUserById(int $id): User
    {
        return $this->userRepository->getById($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createUserKeycloak(array $data)
    {
        $body = [
            'username' => $data['username'],
            'enabled' => true,
            'attributes' => [
                'email' => $data['email'],
                'firstName' => $data['firstName'],
                'lastName' => $data['lastName']
            ],
            'email' => $data['email'],
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'credentials' => [
                [
                    "type" => "password",
                    "value" => $data['password'],
                    "temporary" => false
                ]
            ]
        ];

        try {
            $response = KeycloakAdmin::user()->create([
                'body' => $body,
            ]);

            $newGroup = KeycloakAdmin::group()->create([
                'body' => [
                    'name' => $response->id
                ],
            ]);

            KeycloakAdmin::group()->addUser([
                'id' => $newGroup->id,
                'body' => [
                    'id' => $response->id
                ],
            ]);
        } catch (\Throwable $th) {
            return [
                'code' => 400,
                'message' => trim(str_replace(['"', '}'], "", explode(':', $th->getMessage())[count(explode(':', $th->getMessage())) - 1])),
                'data' => null
            ];
        }

        return [
            'code' => 200,
            'message' => 'Login success',
            'data' => $response
        ];
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function loginUserKeycloak(array $data)
    {
        $body = [
            'grant_type' => 'password',
            'client_id' => self::KEYCLOAK_CLIENT_ID,
            'redirect_uri' => self::KEYCLOAK_REDIRECT_URI,
            'username' => $data['username'],
            'password' => $data['password']
        ];

        try {
            $response = $this->client->post(self::API_URL_KEYCLOAK . self::KEYCLOAK_REALM . self::API_KEYCLOAK_PREFIX, [
                'form_params' => $body
            ]);
        } catch (\Throwable $th) {
            return [
                'code' => 401,
                'message' => trim(str_replace(['"', '}'], "", explode(':', $th->getMessage())[count(explode(':', $th->getMessage())) - 1])),
                'data' => null
            ];
        }

        return [
            'code' => 200,
            'message' => 'Login success',
            'data' => json_decode($response->getBody()->getContents(), true)
        ];
    }

    /**
     * @return mixed
     */
    public function resetPassword()
    {
        $result = KeycloakAdmin::user()->setTemporaryPassword([
            'id' => $this->getKeyCloakUser()['id'],
            'body' => [
                'type' => 'password',
                'value' => '123456',
                'temporary' => false
            ]
        ]);

        return $result;
    }
}
