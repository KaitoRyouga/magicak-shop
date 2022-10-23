<?php

namespace App\Http\Controllers\AdminAPI;

use App\Managers\TemporaryDomainManager;
use App\Http\Requests\TemporaryDomain\GetTemporaryDomainWithSystemDomainRequest;
use App\Http\Requests\TemporaryDomain\UpdateTemporaryDomainRequest;
use App\Http\Requests\TemporaryDomain\AddMoreTemporaryDomainRequest;
use App\Http\Requests\TemporaryDomain\CheckStatusTemporaryDomainRequest;
use Illuminate\Http\JsonResponse;

class ApiTemporaryDomainController extends ApiController
{
    /**
     * @var TemporaryDomainManager
     */
    protected $temporaryDomainManager;

    /**
     * ApiSystemDomainController constructor.
     * @param TemporaryDomainManager $temporaryDomainManager
     */
    public function __construct(TemporaryDomainManager $temporaryDomainManager)
    {
        $this->temporaryDomainManager = $temporaryDomainManager;
    }

    /**
     * @param CheckStatusTemporaryDomainRequest $request
     * @return JsonResponse
     *
     * @OA\post (
     *     path="/admin-api/v1.0/check-status-temporary-domain",
     *     summary="Check status temporary domain",
     *     tags={"TemporaryDomain"},
     *     operationId="checkStatusTemporaryDomain",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="domain_name", type="string", example="magic.test")
     *          )
     *     ),
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
    public function checkStatusTemporaryDomain(CheckStatusTemporaryDomainRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->temporaryDomainManager->checkStatusTemporaryDomain($data);

        return $this->responseJson($result['data'], $result['message'], $result['code']);
    }

    /**
     * @param GetTemporaryDomainWithSystemDomainRequest $request
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/temporary-domain-with-system-domain",
     *     summary="Get all temporary domains with system domain",
     *     tags={"TemporaryDomain"},
     *     operationId="getTemporaryDomainsWithSystemDomain",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="domain_name", type="string", example="magic.test")
     *          )
     *     ),
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
    public function getAllTemporaryDomainWithSystemDomain(GetTemporaryDomainWithSystemDomainRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->temporaryDomainManager->getAllTemporaryDomainWithSystemDomain($data);

        return $this->responseJson($result['data'], $result['message'], $result['code']);
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/manage-temporary-domain",
     *     summary="Get manage temporary domain",
     *     tags={"TemporaryDomain"},
     *     operationId="getManageTemporaryDomain",
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
    public function getManageTemporaryDomain(): JsonResponse
    {
        $result = $this->temporaryDomainManager->getManageTemporaryDomain();

        return $this->responseJson($result['data'], $result['message'], $result['code']);
    }

    /**
     * @param UpdateTemporaryDomainRequest $request
     * @return JsonResponse
     *
     * @OA\Put (
     *     path="/admin-api/v1.0/temporary-domain-with-system-domain",
     *     summary="Update temporary domain with system domain",
     *     tags={"TemporaryDomain"},
     *     operationId="updateTemporaryDomainWithSystemDomain",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="domain_name", type="string", example="magic.test")
     *              @OA\Property(property="ip", type="string", example="1.1.1.1")
     *          )
     *     ),
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
    public function updateTemporaryDomainWithSystemDomain(UpdateTemporaryDomainRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->temporaryDomainManager->updateTemporaryDomainWithSystemDomain($data);

        return $this->responseJson($result['data'], $result['message'], $result['code']);
    }

    /**
     * @param AddMoreTemporaryDomainRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/admin-api/v1.0/add-more-temporary-domain",
     *     summary="Add more temporary domain",
     *     tags={"TemporaryDomain"},
     *     operationId="addMoreTemporaryDomain",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="domain_name", type="string", example="magic.test")
     *          )
     *     ),
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
    public function addMoreTemporaryDomain(AddMoreTemporaryDomainRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->temporaryDomainManager->addMoreTemporaryDomain($data);

        return $this->responseJson($result['data'], $result['message'], $result['code']);
    }
}
