<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserWebsite\InitUserWebsiteRequest;
use App\Http\Requests\UserWebsite\CreateUserWebsiteRequest;
use App\Http\Requests\UserWebsite\UpdateDomainUserWebsiteRequest;
use App\Http\Requests\UserWebsite\UpgradePlanRequest;
use App\Http\Requests\UserWebsite\CheckBusinessNameExistRequest;
use App\Http\Requests\UserWebsite\ChoooseDomainRequest;
use App\Http\Requests\WebsiteMessage\UpdateWebsiteMessageRequest;
use App\Managers\DomainManager;
use App\Managers\UserWebsiteManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApiUserWebsiteController extends ApiController
{
    /**
     * @var UserWebsiteManager
     */
    protected $userWebsiteManager;

    /**
     * @var DomainManager
     */
    protected $domainManager;

    /**
     * ApiUserWebsiteController constructor.
     * @param UserWebsiteManager $userWebsiteManager
     * @param DomainManager $domainManager
     */
    public function __construct(UserWebsiteManager $userWebsiteManager, DomainManager $domainManager)
    {
        $this->userWebsiteManager = $userWebsiteManager;
        $this->domainManager = $domainManager;
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/user-website",
     *     summary="get all websites in user",
     *     tags={"User Websites"},
     *     operationId="userWebsite",
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
    public function getUserWebsites(): JsonResponse
    {
        try {

            $result = $this->userWebsiteManager->getUserWebsites();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/user-website/{id}",
     *     summary="get website by id",
     *     tags={"User Websites"},
     *     operationId="userWebsite",
     *     @OA\Parameter(
     *          name="id",
     *          description="User website id",
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
    public function getUserWebsite(int $id): JsonResponse
    {
        try {

            $result = $this->userWebsiteManager->getUserWebsiteById($id);
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
     *     path="/api/v1.0/user-website-pending/{id}",
     *     summary="Delete user website pending by id",
     *     tags={"User Websites"},
     *     operationId="userWebsiteDeletePending",
     *     @OA\Parameter(
     *          name="id",
     *          description="User website id",
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
    public function deleteByUserIdAndUserWebsiteId(int $id): JsonResponse
    {
        try {

            $data = [
                'user_id' => Auth::id(),
                'user_website_id' => $id
            ];

            $this->userWebsiteManager->deleteByUserIdAndUserWebsiteId($data);
            return $this->responseJson(null, trans('Delete website successful!'));
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Delete (
     *     path="/api/v1.0/user-website/{id}",
     *     summary="Delete user website by id",
     *     tags={"User Websites"},
     *     operationId="userWebsiteDelete",
     *     @OA\Parameter(
     *          name="id",
     *          description="User website id",
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
    public function deleteUserWebsiteById(int $id): JsonResponse
    {
        try {

            $data = [
                'created_id' => Auth::id(),
                'user_website_id' => $id
            ];

            $result = $this->userWebsiteManager->deleteUserWebsiteById($data);

            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CreateUserWebsiteRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/user-website",
     *     summary="Create user website",
     *     tags={"User Websites"},
     *     operationId="createUserWebsite",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="business_name", type="string", example="magic"),
     *              @OA\Property(property="hosting_plan_id", type="integer", example="1"),
     *              @OA\Property(property="hosting_app_storage", type="integer", example="20"),
     *              @OA\Property(property="hosting_db_storage", type="integer", example="10"),
     *              @OA\Property(property="dc_location_id", type="integer", example="1"),
     *              @OA\Property(property="domain_type_id", type="integer", example="2"),
     *              @OA\Property(property="template_id", type="integer", example="2"),
     *              @OA\Property(property="system_domain_id", type="integer", example="2"),
     *              @OA\Property(property="domain_name", type="string", example="magic"),
     *              @OA\Property(property="total_price", type="string", example="60.50"),
     *              @OA\Property(property="is_transfer", type="integer", example="0"),
     *              @OA\Property(property="auth_key", type="string", example="123456"),
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
    public function createUserWebsite(CreateUserWebsiteRequest $request): JsonResponse
    {
        try {

            $data = [
                'created_id' => Auth::id(),
            ];

            $validated = $request->validated();

            $data = array_merge($data, $validated);

            $result = $this->userWebsiteManager->createUserWebsite($data);

            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param InitUserWebsiteRequest $request
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Put (
     *     path="/api/v1.0/user-website/{id}",
     *     summary="Finish setup user website, request from python",
     *     tags={"User Websites"},
     *     operationId="UpdateUserWebsite",
     *     @OA\Parameter(
     *          name="id",
     *          description="User website id",
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
     *              @OA\Property(property="status", type="string", example="initial"),
     *              @OA\Property(property="current_tasks", type="string", example="initial"),
     *              @OA\Property(property="hosting_created_date", type="Date", example="2022-01-03"),
     *              @OA\Property(property="hosting_expired_date", type="Date", example="2022-01-03"),
     *              @OA\Property(property="website_url", type="string", example="https://magicak.com"),
     *              @OA\Property(property="website_message_id", type="integer", example="1"),
     *              @OA\Property(property="hosting_ip", type="string", example="x.x.x.x"),
     *          )
     *     ),
     *
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
    public function initUserWebsite(InitUserWebsiteRequest $request, int $id): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->userWebsiteManager->initUserWebsite($id, $data);

            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CheckBusinessNameExistRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/trigger-init-user-website",
     *     summary="Trigger setup up first step website",
     *     tags={"User Websites"},
     *     operationId="triggerInitUserWebsite",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="business_name", type="string", example="nameabc"),
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
    public function triggerInitUserWebsite(CheckBusinessNameExistRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->userWebsiteManager->triggerInitUserWebsite($data);

            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/check-business-name",
     *     summary="check business name",
     *     tags={"User"},
     *     operationId="checkBusinessName",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="business_name", type="string", example="magic")
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
    public function checkBusinessNameExist(CheckBusinessNameExistRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            $result = $this->userWebsiteManager->checkBusinessNameExist($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param ChoooseDomainRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/choose-domain",
     *     summary="choose domain",
     *     tags={"User"},
     *     operationId="chooseDomain",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="user_website_id", type="integer", example="1"),
     *              @OA\Property(property="domain_name", type="string", example="magic"),
     *              @OA\Property(property="is_temporary", type="integer", example="1"),
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
    public function chooseDomain(ChoooseDomainRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            $result = $this->userWebsiteManager->chooseDomain($data);
            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param UpdateDomainUserWebsiteRequest $request
     * @return JsonResponse
     *
     * @OA\Put (
     *     path="/api/v1.0/update-domain-user-website",
     *     summary="update domain user website",
     *     tags={"User"},
     *     operationId="updateDomainUserWebsite",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="domain_name", type="string", example="magicak123.com"),
     *              @OA\Property(property="user_website_id", type="integer", example="1"),
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
    public function updateDomainUserWebsite(UpdateDomainUserWebsiteRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            $result = $this->userWebsiteManager->updateDomainUserWebsite($data);

            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param upgradePlanRequest $request
     * @return JsonResponse
     *
     * @OA\Put (
     *     path="/api/v1.0/upgrade-plan",
     *     summary="upgrade plan",
     *     tags={"User"},
     *     operationId="upgradePlan",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="user_website_id", type="integer", example=1),
     *              @OA\Property(property="hosting_plan_id", type="integer", example=1),
     *              @OA\Property(property="latest_purchased_package", type="integer", example=1),
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
    public function upgradePlan(upgradePlanRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            $result = $this->userWebsiteManager->upgradePlan($data);
            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param UpdateWebsiteMessageRequest $request
     * @return JsonResponse
     *
     * @OA\Put (
     *     path="/api/v1.0/update-website-message",
     *     summary="update website message",
     *     tags={"User"},
     *     operationId="updateWebsiteMessage",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="user_website_id", type="integer", example=1),
     *              @OA\Property(property="message", type="string", example="test message"),
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
    public function updateWebsiteMessage(UpdateWebsiteMessageRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            $result = $this->userWebsiteManager->updateWebsiteMessage($data);
            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Put (
     *     path="/api/v1.0/user-website-updating-domain",
     *     summary="user website updating domain",
     *     tags={"User"},
     *     operationId="userWebsiteUpdatingDomain",
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
    public function getUserWebsiteUpdatingDomain(): JsonResponse
    {
        try {

            $result = $this->userWebsiteManager->getUserWebsiteUpdatingDomain();
            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }
}
