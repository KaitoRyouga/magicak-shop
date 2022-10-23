<?php

namespace App\Repositories;

use App\Models\User;
use KeycloakAdm\Facades\KeycloakAdmin;

class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @param $username
     * return mixed
     */
    public function checkUsernameExist($username)
    {
        return $this->model->where('keycloak_username', $username)->first();
    }

    /**
     * @param string $userId
     * @param string $username
     * @return User
     */
    public function createUser(string $userId, string $username): User
    {
        $data = [
            'keycloak_userId' => $userId,
            'keycloak_username' => $username,
            'active' => 1,
        ];

        return $this->updateOrCreate(null, $data);
    }

    /**
     * @param $data
     * @param $id
     *
     */
    public function updateUser($data, $id): void
    {
        KeycloakAdmin::user()->update([
            'id' => $id,
            'body' => $data
        ]);
    }
}
