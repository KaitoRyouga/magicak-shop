<?php

namespace App\Traits;

use Exception;

trait CustomException
{
    /**
     * @param string $message
     * @param array|null $data
     * @param Exception|null $e
     * @throws Exception
     */
    public function invalidReq(string $message, ?array $data = null, ?Exception $e = null)
    {
        $this->getResponse(400, $message, $data, $e);
    }

    /**
     * @param string $message
     * @param array|null $data
     * @param Exception|null $e
     * @throws Exception
     */
    public function notFoundReq(string $message, ?array $data = null, ?Exception $e = null)
    {
        $this->getResponse(404, $message, $data, $e);
    }

    /**
     * @param string $message
     * @param array|null $data
     * @param Exception|null $e
     * @throws Exception
     */
    public function unauthorizedReq(string $message, ?array $data = null, ?Exception $e = null)
    {
        $this->getResponse(401, $message, $data, $e);
    }

    /**
     * @param string $message
     * @param array|null $data
     * @param Exception|null $e
     * @throws Exception
     */
    public function forbiddenReq(string $message, ?array $data = null, ?Exception $e = null)
    {
        $this->getResponse(403, $message, $data, $e);
    }

    /**
     * @param string $message
     * @param array|null $data
     * @param Exception|null $e
     * @throws Exception
     */
    public function internalErrorReq(string $message, ?array $data = null, ?Exception $e = null)
    {
        $this->getResponse(500, $message, $data, $e);
    }

    /**
     * @param string $message
     * @param array|null $data
     * @param Exception|null $e
     * @throws Exception
     */
    public function badGatewayReq(string $message, ?array $data = null, ?Exception $e = null)
    {
        $this->getResponse(502, $message, $data, $e);
    }

    /**
     * @param string $message
     * @param array|null $data
     * @param Exception|null $e
     * @throws Exception
     */
    public function gatewayTimeoutReq(string $message, ?array $data = null, ?Exception $e = null)
    {
        $this->getResponse(504, $message, $data, $e);
    }

    /**
     * @param int $code
     * @param string $message
     * @param array|null $data
     * @param Exception|null $e
     * @throws Exception
     */
    private function getResponse(int $code, string $message, ?array $data = null, ?Exception $e = null)
    {
        $response = [
            'code' => $code,
            'message' => $message,
            'response' => $data
        ];

        response()->json($response, $code)->send();

        if ($e) throw $e;

        exit;
    }
}
