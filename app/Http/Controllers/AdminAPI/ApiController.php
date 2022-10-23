<?php

namespace App\Http\Controllers\AdminAPI;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Response json data default
     * @return HttpJsonResponse
     */

    public function index(): HttpJsonResponse
    {
        return response()->json([
            'Welcome to Magicak API',
            'Version 1',
        ]);
    }

    /**
     * @param array|Collection|null $data
     * @param string|null $message
     * @param int|null $code
     * @return HttpJsonResponse
     */
    protected function responseJson($data = null, ?string $message = null, ?int $code = null): HttpJsonResponse
    {
        $response = [
            'code'     => null,
            'response' => null,
            'message'  => null,
        ];

        if ($data) {
            $response['response'] = $data;
        }

        if ($message) {
            $response['message'] = $message;
        }

        $response['code'] = $code ?? 200;

        return response()->json($response);
    }
}
