<?php

namespace App\Http\Controllers\AdminAPI;

use App\Managers\DomainManager;
use Illuminate\Http\JsonResponse;

class ApiDomainController extends ApiController
{
    /**
     * @var DomainManager
     */
    protected $domainManager;

    /**
     * ApiDomainController constructor.
     * @param DomainManager $domainManager
     */
    public function __construct(DomainManager $domainManager)
    {
        $this->domainManager = $domainManager;
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/domain",
     *     summary="get all domain by admin",
     *     tags={"Domain"},
     *     operationId="getDomain",
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="not found"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *     )
     * )
     */
    public function getDomains(): JsonResponse
    {
        $result = $this->domainManager->getDomainsByAdmin();

        return $this->responseJson($result['data'], $result['message'], $result['code']);
    }
}
