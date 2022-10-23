<?php

namespace App\Http\Controllers\Api;

use App\Managers\SystemDomainManager;
use App\Http\Requests\SystemDomain\CreateSystemDomainRequest;
use App\Http\Requests\SystemDomain\UpdateSystemDomainRequest;
use App\Http\Requests\SystemDomain\CheckSystemDomainExistRequest;
use Illuminate\Http\JsonResponse;

class ApiSystemDomainController extends ApiController
{
    /**
     * @var SystemDomainManager
     */
    protected $systemDomainManager;

    /**
     * ApiSystemDomainController constructor.
     * @param SystemDomainManager $systemDomainManager
     */
    public function __construct(SystemDomainManager $systemDomainManager)
    {
        $this->systemDomainManager = $systemDomainManager;
    }

    /**
     * @param CheckSystemDomainExistRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/admin-api/v1.0/check-system-domains",
     *     summary="check system domain name",
     *     tags={"Admin system domain"},
     *     operationId="checkSystemDomainName",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="domain_name", type="string", example="magicak.com"),
     *              @OA\Property(property="domain_type_id", type="string", example="1"),
     *              @OA\Property(property="domain_register_from", type="string", example="Vodien")
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
    public function checkSystemDomainExist(CheckSystemDomainExistRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->systemDomainManager->checkSystemDomainExist($data);
    
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CreateSystemDomainRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/admin-api/v1.0/system-domains",
     *     summary="create system domain",
     *     tags={"Admin system domain"},
     *     operationId="createSystemDomain",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="domain_name", type="string", example="test123.com"),
     *              @OA\Property(property="domain_type_id", type="integer", example="1"),
     *              @OA\Property(property="domain_register_from", type="string", example="Vodien"),
     *              @OA\Property(property="ip", type="string", example="1.1.1.1"),
     *              @OA\Property(property="active", type="integer", example="1")
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
    public function createSystemDomain(CreateSystemDomainRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            $result = $this->systemDomainManager->createSystemDomain($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param UpdateSystemDomainRequest $request
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Put (
     *     path="/admin-api/v1.0/system-domains/{id}",
     *     summary="update system domain",
     *     tags={"Admin system domain"},
     *     operationId="updateSystemDomain",
     *     @OA\Parameter(
     *          name="id",
     *          description="system domain id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="domain_name", type="string", example="test123.com"),
     *              @OA\Property(property="domain_type_id", type="integer", example="1"),
     *              @OA\Property(property="domain_register_from", type="string", example="Vodien"),
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
    public function updateSystemDomain(UpdateSystemDomainRequest $request, int $id): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->systemDomainManager->updateSystemDomain($data, $id);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Delete (
     *     path="/admin-api/v1.0/system-domains/{id}",
     *     summary="delete system domain",
     *     tags={"Admin system domain"},
     *     operationId="deleteSystemDomain",
     *     @OA\Parameter(
     *          name="id",
     *          description="data center system domain id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
    public function deleteSystemDomain(int $id): JsonResponse
    {
        try {

            $result = $this->systemDomainManager->deleteSystemDomain($id);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/system-domains",
     *     summary="list system domain",
     *     tags={"Admin system domain"},
     *     operationId="listSystemDomain",
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
    public function listSystemDomain(): JsonResponse
    {
        try {

            $result = $this->systemDomainManager->listSystemDomains();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/system-domain-dropdowns",
     *     summary="list system domain for dropdown list",
     *     tags={"Admin system domain Dropdown"},
     *     operationId="listSystemDomainDropdown",
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
    public function listSystemDomainDropdowns(): JsonResponse
    {
        try {

            $result = $this->systemDomainManager->listSystemDomainDropdowns();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }
}
