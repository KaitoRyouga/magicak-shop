<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Carts\CreateCartsRequest;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Carts\UpdateCartsRequest;
use App\Managers\CartManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApiCartController extends ApiController
{
    /**
     * @var CartManager
     */
    protected $cartManager;

    /**
     * ApiCartController constructor.
     * @param CartManager $cartManager
     */
    public function __construct(CartManager $cartManager)
    {
        $this->cartManager = $cartManager;
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/carts",
     *     summary="get all carts with user",
     *     tags={"Carts"},
     *     operationId="getCarts",
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

    public function getCart(): JsonResponse
    {
        try {

            $result = $this->cartManager->getCart();

            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param AddToCartRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/v1.0/add-to-cart",
     *     summary="Add to cart",
     *     tags={"Cart"},
     *     operationId="addToCart",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="product_id", type="integer", example="1"),
     *              @OA\Property(property="quantity", type="integer", example="1")
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
    public function addToCart(AddToCartRequest $request): JsonResponse
    {
        try {

            $data = [
                'created_id' => Auth::id()
            ];

            $validated = $request->validated();

            $data = array_merge($data, $validated);

            $result = $this->cartManager->addToCart($data);

            return $this->responseJson($result['data'], $result['message'], $result['code']);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }
}
