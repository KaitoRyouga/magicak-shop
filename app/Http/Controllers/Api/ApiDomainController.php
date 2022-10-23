<?php

namespace App\Http\Controllers\Api;

use App\Managers\DomainManager;
use App\Http\Requests\Domain\CheckDomainNameExistRequest;
use App\Http\Requests\Domain\CreateDomainRequest;
use App\Http\Requests\Domain\TriggerInitDomainRequest;
use Illuminate\Support\Facades\Auth;
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
     * @param CheckDomainNameExistRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/check-domain-name",
     *     summary="check domain name",
     *     tags={"Domain"},
     *     operationId="checkDomainName",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="domain_name", type="string", example="magic"),
     *              @OA\Property(property="domain_type_id", type="integer", example=2),
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
    public function checkDomainNameExist(CheckDomainNameExistRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->domainManager->checkDomainNameExist($data);
    
            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/domain",
     *     summary="get all domain by user",
     *     tags={"Domain"},
     *     operationId="getAllDomainByUser",
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
        try {

            $result = $this->domainManager->getDomains();

            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/list-domain-update",
     *     summary="get list domain update",
     *     tags={"Domain"},
     *     operationId="getListDomainUpdate",
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
    public function getListDomainUpdate(): JsonResponse
    {
        try {

            $result = $this->domainManager->getListDomainUpdate();

            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CreateDomainRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/domain",
     *     summary="create domain",
     *     tags={"Domain"},
     *     operationId="createDomain",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="domain_name", type="string", example="magic"),
     *              @OA\Property(property="domain_type_id", type="integer", example=2),
     *              @OA\Property(property="price", type="integer", example=2),
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
    public function createDomain(CreateDomainRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->domainManager->updateOrCreateDomain($data);
    
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param int $domain_id
     * @return JsonResponse
     *
     * @OA\Delete (
     *     path="/api/v1.0/domain",
     *     summary="Delete domain",
     *     tags={"Domain"},
     *     operationId="deleteDomain",
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
    public function deleteDomain(int $domain_id): JsonResponse
    {
        try {

            $data = [
                'user_id' => Auth::id(),
                'id' => $domain_id
            ];
    
            $result = $this->domainManager->deleteDomainByManageDomain($data);
            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param TriggerInitDomainRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/trigger-init-domain",
     *     summary="Trigger init domain",
     *     tags={"Domain"},
     *     operationId="triggerInitDomain",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="domain_name", type="string", example="nameabc.com"),
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
    public function triggerInitDomain(TriggerInitDomainRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->domainManager->triggerInitDomain($data);
    
            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }
}
