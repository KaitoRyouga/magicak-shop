<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\HostingClusters\CreateHostingClusterRequest;
use App\Http\Requests\HostingClusters\UpdateHostingClusterRequest;
use App\Managers\HostingClusterManager;
use Illuminate\Http\JsonResponse;

class ApiClusterController extends ApiController
{
    /**
     * @var HostingClusterManager
     */
    protected $hostingClusterManager;

    /**
     * ApiClusterApiController constructor.
     * @param HostingClusterManager $hostingClusterManager
     */
    public function __construct(HostingClusterManager $hostingClusterManager)
    {
        $this->hostingClusterManager = $hostingClusterManager;
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/cluster-domains",
     *     summary="get all cluster domains",
     *     tags={"Admin Cluster Domain"},
     *     operationId="getHostingClusters",
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
    public function getHostingClusters(): JsonResponse
    {
        try {

            $hostingClusters = $this->hostingClusterManager->getHostingClusters();
            return $this->responseJson($hostingClusters);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/cluster-domain-dropdowns",
     *     summary="get all cluster domains for dropdown list",
     *     tags={"Admin Cluster Domain Dropdowns"},
     *     operationId="getHostingClusters",
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
    public function getHostingClusterDropdowns(): JsonResponse
    {
        try {

            $hostingClusters = $this->hostingClusterManager->getHostingClusterDropdowns();
            return $this->responseJson($hostingClusters);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CreateHostingClusterRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/admin-api/v1.0/cluster-domains",
     *     summary="create cluster domain",
     *     tags={"Admin Cluster Domain"},
     *     operationId="createHostingCluster",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="domain", type="string", example="magic.test"),
     *              @OA\Property(property="sort", type="integer", example="1"),
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
    public function createHostingCluster(CreateHostingClusterRequest $request): JsonResponse
    {
        try {

            $data = [
                'active' => $this->hostingClusterManager::STATUS_ACTIVE,
            ];
    
            $validated = $request->validated();
            $data = array_merge($data, $validated);
            $result = $this->hostingClusterManager->createHostingCluster($data);
    
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param UpdateHostingClusterRequest $request
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Put (
     *     path="/admin-api/v1.0/cluster-domains/{id}",
     *     summary="update cluster domain",
     *     tags={"Admin Cluster Domain"},
     *     operationId="updateHostingCluster",
     *     @OA\Parameter(
     *          name="id",
     *          description="cluster domain id",
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
     *              @OA\Property(property="domain", type="string", example="magic.test"),
     *              @OA\Property(property="sort", type="integer", example="1"),
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
    public function updateHostingCluster(UpdateHostingClusterRequest $request, int $id): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->hostingClusterManager->updateHostingCluster($id, $data);
    
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
     *     path="/admin-api/v1.0/cluster-domains/{id}",
     *     summary="delete cluster domain",
     *     tags={"Admin Cluster Domain"},
     *     operationId="deleteHostingCluster",
     *     @OA\Parameter(
     *          name="id",
     *          description="cluster domain id",
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
    public function deleteHostingCluster(int $id): JsonResponse
    {
        try {

            $result = $this->hostingClusterManager->deleteHostingCluster($id);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }
}
