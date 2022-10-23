<?php

namespace App\Http\Controllers\Api;

use App\Managers\SettingManager;
use Illuminate\Http\JsonResponse;

class ApiSettingController extends ApiController
{
    /**
     * @var SettingManager
     */
    protected $settingManager;

    /**
     * ApiSettingController constructor.
     * @param SettingManager $settingManager
     */
    public function __construct(SettingManager $settingManager)
    {
        $this->settingManager = $settingManager;
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/settings",
     *     summary="get settings",
     *     tags={"Setting"},
     *     operationId="getSettings",
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
    public function getSettings(): JsonResponse
    {
        $result = $this->settingManager->getFirst();
        return $this->responseJson($result);
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/setting-provider-domain",
     *     summary="get setting provider domain",
     *     tags={"Setting"},
     *     operationId="getSettingProviderDomain",
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
    public function getSettingProviderDomain(): JsonResponse
    {
        try {

            $result = $this->settingManager->getProviderDomain();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }
}
