<?php

namespace App\Http\Middleware;

use App\Managers\UserManager;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CheckUser
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * checkUser middleware constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $keycloakUser = Cache::remember('user_' . Auth::token(), Carbon::now()->addMinutes(15), function () {
            return $this->userManager->getKeyCloakUser();
        });

        $username = $keycloakUser['username'] ?? null; // get username from keycloak
        $userId = $keycloakUser['id'] ?? null; // get userid from keycloak

        if ($userId && $username) {

            $user = Cache::remember('user_' . $username, Carbon::now()->addMinutes(15), function () use ($username) {
                return $this->userManager->checkUsernameExist($username);
            });

            if (!$user) {
                $user = $this->userManager->createUser($userId, $username);
            }

            // update last login time
            if (!empty($keycloakUser['sessions'])) {
                $lastLogin = max(array_column($keycloakUser['sessions'], 'start'));
                $lastLogin = Carbon::createFromTimestampMs($lastLogin);

                if ($lastLogin != $user->last_login) {
                    $user->last_login = $lastLogin;
                    $user->save();
                }
            }

            Auth::setUser($user);
        }

        return $next($request);
    }
}
