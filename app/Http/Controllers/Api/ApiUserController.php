<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UploadAvatarRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\AddToGroupRequest;
use App\Managers\UserManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Queue;

class ApiUserController extends ApiController
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * ApiProductController constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/profile",
     *     summary="get user profile",
     *     tags={"User"},
     *     operationId="userProfile",
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
    public function getProfile(): JsonResponse
    {
        try {

            $user = $this->userManager->getProfile();
            return $this->responseJson($user);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param UpdateUserRequest $request
     * @return JsonResponse
     *
     * @OA\Put (
     *     path="/api/v1.0/user",
     *     summary="Update user",
     *     tags={"User"},
     *     operationId="UpdateUser",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="firstName", type="string", example="local-first-name"),
     *              @OA\Property(property="lastName", type="string", example="loca-last-name"),
     *              @OA\Property(property="company", type="string", example="company"),
     *              @OA\Property(property="email", type="string", example="test@gmail.com"),
     *              @OA\Property(property="birth_date", type="Date", example="2000-01-03"),
     *              @OA\Property(property="phone", type="string", example="+6595895857"),
     *              @OA\Property(property="language", type="string", example="French"),
     *              @OA\Property(property="gender", type="string", example="Male"),
     *              @OA\Property(property="twitter", type="string", example="https://www.twitter.com/adoptionism744"),
     *              @OA\Property(property="facebook", type="string", example="https://www.facebook.com/adoptionism664"),
     *              @OA\Property(property="instagram", type="string", example="https://www.instagram.com/adopt-ionism744"),
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
    public function updateUser(UpdateUserRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            $result = $this->userManager->updateUser($data);

            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param UpdatePasswordRequest $request
     * @return JsonResponse
     *
     * @OA\Put (
     *     path="/api/v1.0/update-password",
     *     summary="Update password",
     *     tags={"User"},
     *     operationId="UpdatePassword",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="password", type="string", example="123456789a"),
     *              @OA\Property(property="verify_password", type="string", example="123456789a"),
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
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            $this->userManager->updatePassword($data);

            return $this->responseJson(null, trans('Update password successful!'));
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param UploadAvatarRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/upload-avatar",
     *     summary="upload avatar",
     *     tags={"User"},
     *     operationId="uploadAvatar",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="avatar", type="file"),
     *                  @OA\Property( property="_method", example="PUT"),
     *              )
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
    public function uploadAvatar(UploadAvatarRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $data['attributes']['avatar'] = $request->file('avatar')->store('avatars');
            unset($data['avatar']);

            $result = $this->userManager->uploadAvatar($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/login",
     *     summary="login",
     *     tags={"User"},
     *     operationId="login",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string", example="test@gmail.com"),
     *              @OA\Property(property="password", type="string", example="123456789a"),
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
    public function login(LoginRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            $result = $this->userManager->loginUserKeycloak($data);
            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/register",
     *     summary="register",
     *     tags={"User"},
     *     operationId="register",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string", example="test@gmail.com"),
     *              @OA\Property(property="password", type="string", example="123456789a"),
     *              @OA\Property(property="username", type="string", example="test"),
     *              @OA\Property(property="firstName", type="string", example="kaito"),
     *              @OA\Property(property="lastName", type="string", example="ryouga"),
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
    public function register(RegisterRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            $result = $this->userManager->createUserKeycloak($data);
            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/test-rabbitmq",
     *     summary="Test Rabbit MQ",
     *     tags={"User Websites"},
     *     operationId="testRabbitMQ",
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
    public function testRabbitMQ(): JsonResponse
    {
        try {

            // $product = $this->userManager->getProductById(1);

            // if ($product) {
            //     // push message to rabbit mq
            //     $this->userManager->pushPythonMessageData($product);
            // }
            $data = [
                'website_id' => 1
            ];

            Queue::pushRaw(json_encode($data));

            return $this->responseJson(null, trans('Trigger init website successful!'));
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/check-push-noti",
     *     summary="Check push noti",
     *     tags={"User Websites"},
     *     operationId="checkPushNoti",
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
    public function checkPushNotification(): JsonResponse
    {
        try {

            $data = [
                'title' => 'Test title',
                'body' => 'Test body'
            ];

            $queueManager = app('queue');
            $queue = $queueManager->connection('rabbitmq');
            $queue->pushRaw(json_encode($data));

            // Queue::pushRaw(json_encode($data))->onConnection('rabbitmq');

            return $this->responseJson(null, 'test');
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/reset-password",
     *     summary="Reset password",
     *     tags={"User"},
     *     operationId="resetPassword",
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
    public function resetPassword(): JsonResponse
    {
        try {

            $result = $this->userManager->resetPassword();

            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    public function testBug()
    {
        try {

            $result = $this->userManager->a(0);

            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param AddToGroupRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/add-to-group",
     *     summary="Add user to group",
     *     tags={"User"},
     *     operationId="addToGroup",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="group_id", type="string", example="255a6503-6d26-428e-8bf2-4d569a36cfa4"),
     *              @OA\Property(property="username", type="string", example="test"),
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
    public function addToGroup(AddToGroupRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            $result = $this->userManager->addToGroup($data);
            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

}
