<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Payment\CapturePaypalOrderRequest;
use App\Http\Requests\Payment\CapturePaypalOrderDomainRequest;
use App\Http\Requests\Payment\CapturePaypalOrderUpgradePlanRequest;
use App\Http\Requests\Payment\CreatePaypalOrderRequest;
use App\Http\Requests\Payment\CreatePaypalOrderDomainRequest;
use App\Http\Requests\Payment\CreatePaypalOrderUpgradePlanRequest;
use App\Http\Requests\Payment\CreateStripeOrderRequest;
use App\Http\Requests\Payment\CreateStripeOrderDomainRequest;
use App\Http\Requests\Payment\CreateStripeOrderUpgradePlanRequest;
use App\Managers\PaymentTransactionManager;
use Illuminate\Http\JsonResponse;

class ApiPaymentTransactionController extends ApiController
{
    /**
     * @var PaymentTransactionManager
     */
    protected $paymentTransactionManager;

    /**
     * ApiPaymentTransactionController constructor.
     * @param PaymentTransactionManager $paymentTransactionManager
     */
    public function __construct(PaymentTransactionManager $paymentTransactionManager)
    {
        $this->paymentTransactionManager = $paymentTransactionManager;
    }

    /**
     * @param CreateStripeOrderRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/create-stripe-order",
     *     summary="Create Stripe order",
     *     tags={"Payment"},
     *     operationId="createStripeOrder",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="stripeToken", type="string", example="tok_visa"),
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
    public function createStripeOrder(CreateStripeOrderRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->paymentTransactionManager->createStripeOrder($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CreateStripeOrderDomainRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/create-stripe-order-domain",
     *     summary="Create Stripe order domain",
     *     tags={"Payment"},
     *     operationId="createStripeOrderDomain",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="stripeToken", type="string", example="tok_visa"),
     *              @OA\Property(property="domain_id", type="integer", example="1"),
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
    public function createStripeOrderDomain(CreateStripeOrderDomainRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->paymentTransactionManager->createStripeOrderDomain($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

        /**
     * @param CreateStripeOrderUpgradePlanRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/create-stripe-order-upgrade-plan",
     *     summary="Create Stripe order upgrade plan",
     *     tags={"Payment"},
     *     operationId="createStripeOrderUpgradePlan",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="stripeToken", type="string", example="tok_visa"),
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
    public function createStripeOrderUpgradePlan(CreateStripeOrderUpgradePlanRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->paymentTransactionManager->createStripeOrderUpgradePlan($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CreatePaypalOrderRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/create-paypal-order",
     *     summary="Create Paypal order",
     *     tags={"Payment"},
     *     operationId="createPaypalOrder",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="user_website_id", type="integer", example=1),
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
    public function createPaypalOrder(CreatePaypalOrderRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->paymentTransactionManager->createPaypalOrder($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CreatePaypalOrderDomainRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/create-paypal-order-domain",
     *     summary="Create Paypal order domain",
     *     tags={"Payment"},
     *     operationId="createPaypalOrderDomain",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="domain_id", type="integer", example=1),
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
    public function createPaypalOrderDomain(CreatePaypalOrderDomainRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->paymentTransactionManager->createPaypalOrderDomain($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CreatePaypalOrderUpgradePlanRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/create-paypal-order-upgrade-plan",
     *     summary="Create Paypal order upgrade plan",
     *     tags={"Payment"},
     *     operationId="createPaypalOrderUpgradePlan",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="user_website_id", type="integer", example=1),
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
    public function createPaypalOrderUpgradePlan(CreatePaypalOrderUpgradePlanRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->paymentTransactionManager->createPaypalOrderUpgradePlan($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CapturePaypalOrderRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/capture-paypal-order",
     *     summary="Capture Paypal order",
     *     tags={"Payment"},
     *     operationId="capturePaypalOrder",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="order_id", type="string", example="4J4673250M934241G"),
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
    public function capturePaypalOrder(CapturePaypalOrderRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->paymentTransactionManager->capturePaypalOrder($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CapturePaypalOrderDomainRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/capture-paypal-order-domain",
     *     summary="Capture Paypal order domain",
     *     tags={"Payment"},
     *     operationId="capturePaypalOrderDomain",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="order_id", type="string", example="4J4673250M934241G"),
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
    public function capturePaypalOrderDomain(CapturePaypalOrderDomainRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->paymentTransactionManager->capturePaypalOrderDomain($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CapturePaypalOrderUpgradePlanRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/capture-paypal-order-upgrade-plan",
     *     summary="Capture Paypal order upgrade plan",
     *     tags={"Payment"},
     *     operationId="capturePaypalOrderUpgradePlan",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="order_id", type="string", example="4J4673250M934241G"),
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
    public function capturePaypalOrderUpgradePlan(CapturePaypalOrderRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->paymentTransactionManager->capturePaypalOrderUpgradePlan($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/invoices",
     *     summary="Get invoices",
     *     tags={"Payment"},
     *     operationId="getInvoices",
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
    public function getInvoices(): JsonResponse
    {
        try {

            $result = $this->paymentTransactionManager->getInvoices();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/invoice/{id}",
     *     summary="Get invoice by id",
     *     tags={"Payment"},
     *     operationId="getInvoiceById",
     *     @OA\Parameter(
     *          name="id",
     *          description="invoice id",
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
    public function getInvoiceById(int $id): JsonResponse
    {
        try {

            $result = $this->paymentTransactionManager->getInvoiceById($id);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }
}
