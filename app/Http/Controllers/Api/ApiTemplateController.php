<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TemplateCategories\CreateTemplateCategoriesRequest;
use App\Http\Requests\TemplateCategories\UpdateTemplateCategoriesRequest;
use App\Http\Requests\Template\CreateTemplateRequest;
use App\Http\Requests\Template\UpdateTemplateRequest;
use App\Managers\TemplateManager;
use Illuminate\Http\JsonResponse;

class ApiTemplateController extends ApiController
{
    /**
     * @var TemplateManager
     */
    protected $templateManager;

    /**
     * ApiTemplateController constructor.
     * @param TemplateManager $templateManager
     */
    public function __construct(TemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/template-categories",
     *     summary="get template categories",
     *     tags={"Template"},
     *     operationId="showTemplateCategory",
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
    public function getTemplateCategories(): JsonResponse
    {
        try {

            $result = $this->templateManager->getAllTemplateCategories();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/template-categories/{id}",
     *     summary="get template categories by id",
     *     tags={"Template"},
     *     operationId="showTemplateCategoryById",
     *     @OA\Parameter(
     *          name="id",
     *          description="Template category id",
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
    public function getTemplateCategoryById(int $id): JsonResponse
    {
        try {

            $result = $this->templateManager->getTemplateCategoryById($id);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @param int $id
     *
     * @OA\Get (
     *     path="/api/v1.0/templates/{id}",
     *     summary="get templates with category",
     *     tags={"Template"},
     *     operationId="templateWithCategory",
     *     @OA\Parameter(
     *          name="id",
     *          description="Category id",
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
    public function getTemplateWithCategoryAndSub(int $id): JsonResponse
    {
        try {

            $subcategory = $_GET['subcat'] ?? null;

            $result = $this->templateManager->getTemplateWithCategoryAndSub($id, $subcategory);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/api/v1.0/template-types",
     *     summary="get template type",
     *     tags={"Template"},
     *     operationId="getAlltemplateType",
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
    public function getTemplateTypes(): JsonResponse
    {
        try {

            $result = $this->templateManager->getTemplateTypes();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CreateTemplateCategoriesRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/admin-api/v1.0/template-categories",
     *     summary="create template categories",
     *     tags={"Admin Template Categories"},
     *     operationId="createTemplateCategories",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="template_type_id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="test template categories"),
     *              @OA\Property(property="description", type="string", example="test template categories description"),
     *              @OA\Property(property="sort", type="integer", example=1),
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
    public function createTemplateCategories(CreateTemplateCategoriesRequest $request): JsonResponse
    {
        try {

            $data = [
                'active' => $this->templateManager::STATUS_ACTIVE,
            ];

            $validated = $request->validated();
            $data = array_merge($data, $validated);
            $result = $this->templateManager->createTemplateCategories($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param UpdateTemplateCategoriesRequest $request
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Put (
     *     path="/admin-api/v1.0/template-categories/{id}",
     *     summary="update template categories",
     *     tags={"Admin Template Categories"},
     *     operationId="updateTemplateCategories",
     *     @OA\Parameter(
     *          name="id",
     *          description="Template categories id",
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
     *              @OA\Property(property="template_type_id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="test template categories"),
     *              @OA\Property(property="description", type="string", example="test template categories description"),
     *              @OA\Property(property="sort", type="integer", example=1),
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
    public function updateTemplateCategories(UpdateTemplateCategoriesRequest $request, int $id): JsonResponse
    {
        try {

            $data = $request->validated();
            $result = $this->templateManager->updateTemplateCategories($data, $id);
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
     *     path="/admin-api/v1.0/template-categories/{id}",
     *     summary="delete template categories",
     *     tags={"Admin Template Categories"},
     *     operationId="deleteTemplateCategories",
     *     @OA\Parameter(
     *          name="id",
     *          description="Template categories id",
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
    public function deleteTemplateCategories(int $id): JsonResponse
    {
        try {

            $result = $this->templateManager->deleteTemplateCategories($id);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/template-categories",
     *     summary="list template categories",
     *     tags={"Admin Template Categories"},
     *     operationId="listTemplateCategories",
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
    public function listTemplateCategories(): JsonResponse
    {
        try {

            $result = $this->templateManager->listTemplateCategories();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/template-categories-dropdowns",
     *     summary="list template categories dropdowns",
     *     tags={"Admin Template Categories"},
     *     operationId="listTemplateCategoriesDropdowns",
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
    public function listTemplateCategoriesDropdowns(): JsonResponse
    {
        try {

            $result = $this->templateManager->listTemplateCategoriesDropdowns();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param CreateTemplateRequest $request
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/admin-api/v1.0/template",
     *     summary="create template",
     *     tags={"Admin Template"},
     *     operationId="createTemplate",
     *     @OA\RequestBody(
     *          request="Pet",
     *          description="",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="template_category_id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="test template"),
     *                  @OA\Property(property="description", type="string", example="test template description"),
     *                  @OA\Property(property="version", type="string", example="test template version"),
     *                  @OA\Property(property="url", type="string", example="test template url"),
     *                  @OA\Property(property="price", type="integer", example=10),
     *                  @OA\Property(property="active", type="integer", example=1),
     *                  @OA\Property(property="template_app_storage", type="integer", example=1),
     *                  @OA\Property(property="template_db_storage", type="integer", example=1),
     *                  @OA\Property( property="thumbnail", type="file"),
     *                  @OA\Property( property="capture", type="file"),
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
    public function createTemplate(CreateTemplateRequest $request): JsonResponse
    {
        try {

            $data = [
                'active' => $this->templateManager::STATUS_ACTIVE,
            ];

            $validated = $request->validated();
            $data = array_merge($data, $validated);
            $data['thumbnail'] = $request->file('thumbnail')->store('templates');
            $data['capture'] = $request->file('capture')->store('templates');

            $result = $this->templateManager->createTemplate($data);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @param UpdateTemplateRequest $request
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/admin-api/v1.0/template/{id}",
     *     summary="update template",
     *     tags={"Admin Template"},
     *     operationId="updateTemplate",
     *     @OA\Parameter(
     *          name="id",
     *          description="Template id",
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
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="template_category_id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="test template"),
     *                  @OA\Property(property="description", type="string", example="test template description"),
     *                  @OA\Property(property="version", type="string", example="test template version"),
     *                  @OA\Property(property="url", type="string", example="test template url"),
     *                  @OA\Property(property="price", type="integer", example=10),
     *                  @OA\Property(property="active", type="integer", example=1),
     *                  @OA\Property(property="template_app_storage", type="integer", example=1),
     *                  @OA\Property(property="template_db_storage", type="integer", example=1),
     *                  @OA\Property( property="thumbnail", type="file"),
     *                  @OA\Property( property="capture", type="file"),
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
    public function updateTemplate(UpdateTemplateRequest $request, int $id): JsonResponse
    {
        try {

            $data = $request->validated();

            if ($request->file('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('templates');
            }

            if ($request->file('capture')) {
                $data['capture'] = $request->file('capture')->store('templates');
            }

            $result = $this->templateManager->updateTemplate($data, $id);
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
     *     path="/admin-api/v1.0/template/{id}",
     *     summary="delete template",
     *     tags={"Admin Template"},
     *     operationId="deleteTemplate",
     *     @OA\Parameter(
     *          name="id",
     *          description="Template id",
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
    public function deleteTemplate(int $id): JsonResponse
    {
        try {

            $result = $this->templateManager->deleteTemplate($id);
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get (
     *     path="/admin-api/v1.0/template",
     *     summary="list template",
     *     tags={"Admin Template"},
     *     operationId="listTemplate",
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
    public function listTemplate(): JsonResponse
    {
        try {

            $result = $this->templateManager->listTemplate();
            return $this->responseJson($result);
        } catch (\Throwable $e) {

            return $this->responseJsonError($e->getMessage());
        }
    }
}
