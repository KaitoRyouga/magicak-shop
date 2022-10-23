<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\HostingPlans\CreateHostingPlansRequest;
use App\Http\Requests\HostingPlans\FilterHostingPlansRequest;
use App\Http\Requests\HostingPlans\UpdateHostingPlansRequest;
use App\Managers\HostingManager;
use Illuminate\Http\JsonResponse;

class ApiHostingController extends ApiController
{
    /**
     * @var HostingManager
     */
    protected $hostingManager;

    /**
     * ApiHostingController constructor.
     * @param HostingManager $hostingManager
     */
    public function __construct(HostingManager $hostingManager)
    {
        $this->hostingManager = $hostingManager;
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/hosting-types",
     *     summary="get all hosting types",
     *     tags={"Hosting"},
     *     operationId="hostingTypes",
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

    public function getHostingPlanTypes(): JsonResponse
    {
        try {

            $hostingPlanType = $this->hostingManager->getHostingPlanTypes();
            return $this->responseJson($hostingPlanType);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/hosting-plans",
     *     summary="get all hosting plans",
     *     tags={"Hosting"},
     *     operationId="getHostingPlans",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="location_id", type="integer", example=1),
     *              @OA\Property(property="hosting_type_id", type="integer", example=1),
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

    public function getHostingPlansWithLocationAndType(FilterHostingPlansRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            $hostingPlans = $this->hostingManager->getHostingPlansWithLocationAndType($data);
            return $this->responseJson($hostingPlans);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CreateHostingPlansRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/admin-api/v1.0/hosting-plans",
     *     summary="create hosting plans",
     *     tags={"Admin Hosting Plans"},
     *     operationId="createHostingPlans",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="plan_type_id", type="integer", example=1),
     *              @OA\Property(property="hosting_cluster_ids", type="array", @OA\Items(type="integer", example=1)),
     *              @OA\Property(property="name", type="string", example="test hosting plan"),
     *              @OA\Property(property="description", type="string", example="test hosting plan description"),
     *              @OA\Property(property="ssl", type="integer", example=1),
     *              @OA\Property(property="ram", type="integer", example=1),
     *              @OA\Property(property="cpu", type="integer", example=1),
     *              @OA\Property(property="hosting_app_storage", type="integer", example=1),
     *              @OA\Property(property="hosting_db_storage", type="integer", example=1),
     *              @OA\Property(property="backup", type="integer", example=1),
     *              @OA\Property(property="duration", type="integer", example=1),
     *              @OA\Property(property="price", type="integer", example=5)
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
    public function createHostingPlans(CreateHostingPlansRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->hostingManager->createHostingPlans($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param UpdateHostingPlansRequest $request
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Put (
     *     path="/admin-api/v1.0/hosting-plans/{id}",
     *     summary="update hosting plans",
     *     tags={"Admin Hosting Plans"},
     *     operationId="updateHostingPlans",
     *     @OA\Parameter(
     *          name="id",
     *          description="hosting plans id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="plan_type_id", type="integer", example=1),
     *              @OA\Property(property="hosting_cluster_ids", type="array", @OA\Items(type="integer", example=1)),
     *              @OA\Property(property="name", type="string", example="test hosting plan"),
     *              @OA\Property(property="description", type="string", example="test hosting plan description"),
     *              @OA\Property(property="ssl", type="integer", example=1),
     *              @OA\Property(property="ram", type="integer", example=1),
     *              @OA\Property(property="cpu", type="integer", example=1),
     *              @OA\Property(property="hosting_app_storage", type="integer", example=1),
     *              @OA\Property(property="hosting_db_storage", type="integer", example=1),
     *              @OA\Property(property="backup", type="integer", example=1),
     *              @OA\Property(property="duration", type="integer", example=1),
     *              @OA\Property(property="price", type="integer", example=5)
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
    public function updateHostingPlans(UpdateHostingPlansRequest $request, int $id): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->hostingManager->updateHostingPlans($data, $id);
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
     *     path="/admin-api/v1.0/hosting-plans/{id}",
     *     summary="delete hosting plans",
     *     tags={"Admin Hosting Plans"},
     *     operationId="deleteHostingPlans",
     *     @OA\Parameter(
     *          name="id",
     *          description="hosting plans id",
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
    public function deleteHostingPlans(int $id): JsonResponse
    {
        try {

            $result = $this->hostingManager->deleteHostingPlans($id);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/hosting-plans",
     *     summary="list hosting plans",
     *     tags={"Admin Hosting Plans"},
     *     operationId="listHostingPlans",
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
    public function listHostingPlans(): JsonResponse
    {
        try {

            $result = $this->hostingManager->listHostingPlans();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/hosting-plan-type-dropdowns",
     *     summary="list hosting plan types for dropdown",
     *     tags={"Admin Hosting Plan Types Dropdown"},
     *     operationId="listHostingPlanTypes",
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
    public function listHostingPlanTypeDropdowns(): JsonResponse
    {
        try {

            $result = $this->hostingManager->listHostingPlanTypeDropdowns();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/hosting-plan-platform-dropdowns",
     *     summary="list hosting plan platforms for dropdown",
     *     tags={"Admin Hosting Plan Platforms"},
     *     operationId="listHostingPlanPlatforms",
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
    public function listHostingPlanPlatformDropdowns(): JsonResponse
    {
        try {

            $result = $this->hostingManager->listHostingPlanPlatformDropdowns();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }
}
