<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Product\InitProductRequest;
use App\Http\Requests\Product\UpdateDomainProductRequest;
use App\Http\Requests\Product\UpgradePlanRequest;
use App\Http\Requests\Product\CheckBusinessNameExistRequest;
use App\Http\Requests\Product\ChoooseDomainRequest;
use App\Http\Requests\WebsiteMessage\UpdateWebsiteMessageRequest;
use App\Managers\ProductManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApiProductController extends ApiController
{
    /**
     * @var ProductManager
     */
    protected $productManager;

    /**
     * ApiProductController constructor.
     * @param ProductManager $productManager
     */
    public function __construct(ProductManager $productManager)
    {
        $this->productManager = $productManager;
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/products",
     *     summary="get all products",
     *     tags={"Product"},
     *     operationId="getProducts",
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
    public function getProducts(): JsonResponse
    {
        try {

            $result = $this->productManager->getProducts();
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
     *     operationId="product",
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
    public function getProduct(int $id): JsonResponse
    {
        try {

            $result = $this->productManager->getProductById($id);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }
}
