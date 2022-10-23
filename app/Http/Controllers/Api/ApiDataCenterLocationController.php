<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Location\CreateDataCenterLocationRequest;
use App\Http\Requests\Location\UpdateDataCenterLocationRequest;
use Illuminate\Http\JsonResponse;
use App\Managers\DataCenterLocationManager;

class ApiDataCenterLocationController extends ApiController
{
    /**
     * @var DataCenterLocationManager
     */
    protected $dataCenterLocationManager;

    /**
     * ApiDataCenterLocationController constructor.
     * @param DataCenterLocationManager $dataCenterLocationManager
     */
    public function __construct(DataCenterLocationManager $dataCenterLocationManager)
    {
        $this->dataCenterLocationManager = $dataCenterLocationManager;
    }

    /**
     * @return JsonResponse
     *
     * @param int $id
     *
     * @OA\Get (
     *     path="/api/v1.0/data-center-locations/{id}",
     *     summary="get location with id hosting plan type",
     *     tags={"Location"},
     *     operationId="hostingLocation",
     *     @OA\Parameter(
     *          name="id",
     *          description="Hosting plan type id",
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
    public function getLocationWithHostingPlanType(int $id): JsonResponse
    {
        try {

            $result = $this->dataCenterLocationManager->getLocationWithHostingPlanType($id);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CreateDataCenterLocationRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/admin-api/v1.0/data-center-locations",
     *     summary="create location",
     *     tags={"Admin Location"},
     *     operationId="createDataCenterLocation",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="location", type="string", example="Việt Nam"),
     *              @OA\Property(property="code", type="string", example="VN"),
     *              @OA\Property(property="sort", type="integer", example=7),
     *              @OA\Property(property="active", type="integer", example=1),
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
    public function createDataCenterLocation(CreateDataCenterLocationRequest $request): JsonResponse
    {
        try {

            $data = [
                'active' => $this->dataCenterLocationManager::STATUS_ACTIVE,
            ];
    
            $validated = $request->validated();
            $data = array_merge($data, $validated);
            $result = $this->dataCenterLocationManager->createDataCenterLocation($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param UpdateDataCenterLocationRequest $request
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Put (
     *     path="/admin-api/v1.0/data-center-locations/{id}",
     *     summary="update location",
     *     tags={"Admin Location"},
     *     operationId="updateDataCenterLocation",
     *     @OA\Parameter(
     *          name="id",
     *          description="data center location id",
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
     *              @OA\Property(property="location", type="string", example="Việt Nam"),
     *              @OA\Property(property="code", type="string", example="VN"),
     *              @OA\Property(property="sort", type="integer", example=7),
     *              @OA\Property(property="active", type="integer", example=1),
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
    public function updateDataCenterLocation(UpdateDataCenterLocationRequest $request, int $id): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->dataCenterLocationManager->updateDataCenterLocation($data, $id);
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
     *     path="/admin-api/v1.0/data-center-locations/{id}",
     *     summary="delete location",
     *     tags={"Admin Location"},
     *     operationId="deleteDataCenterLocation",
     *     @OA\Parameter(
     *          name="id",
     *          description="data center location id",
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
    public function deleteDataCenterLocation(int $id): JsonResponse
    {
        try {

            $result = $this->dataCenterLocationManager->deleteDataCenterLocation($id);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/data-center-locations",
     *     summary="list location",
     *     tags={"Admin Location"},
     *     operationId="listDataCenterLocation",
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
    public function listDataCenterLocation(): JsonResponse
    {
        try {

            $result = $this->dataCenterLocationManager->listDataCenterLocations();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/data-center-location-dropdowns",
     *     summary="list dc location for dropdown list",
     *     tags={"Admin Location Dropdown"},
     *     operationId="listDataCenterLocationDropdown",
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
    public function listDataCenterLocationDropdowns(): JsonResponse
    {
        try {

            $result = $this->dataCenterLocationManager->listDataCenterLocationDropdowns();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }
}
