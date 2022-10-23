<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class SocketService
{
    /**
     * Constructor.
     */
    public function __construct() {
    }


    /**
     * @param array $data
     */
    public function pushToMessageSocket(array $data): void
    {
        Redis::publish('channel', json_encode([
            'data' => $data['data'],
            'message' => $data['message'],
            'type' => $data['type'],
            'channel' => isset($data['data']['channel']) ? $data['data']['channel'] : Auth::user()->keycloak_userId
        ]));
    }

}
