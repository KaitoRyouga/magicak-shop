<?php

namespace App\Managers;

use App\Services\SocketService;

class SocketManager extends BaseManager
{
    /**
     * @var SocketService
     */
    protected $socketService;

    /**
     * SocketManager constructor.
     * @param SocketService $socketService
     *
     */
    public function __construct(
        SocketService $socketService
    ) {
        $this->socketService = $socketService;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function pushToMessageSocket(array $data)
    {
        return $this->socketService->pushToMessageSocket($data);
    }
}
