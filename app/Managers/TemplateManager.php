<?php

namespace App\Managers;

use App\Models\Template;
use App\Models\TemplateCategory;
use App\Repositories\TemplateTypeRepository;
use App\Repositories\TemplatePriceRepository;
use App\Repositories\TemplateDiscountRepository;
use App\Repositories\TemplateCategoryRepository;
use App\Repositories\TemplateRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TemplateManager extends BaseManager
{
    /**
     * @var TemplateTypeRepository
     */
    protected $templateTypeRepository;

    /**
     * @var TemplatePriceRepository
     */
    protected $templatePriceRepository;

    /**
     * @var TemplateDiscountRepository
     */
    protected $templateDiscountRepository;

    /**
     * @var TemplateCategoryRepository
     */
    protected $templateCategoryRepository;

    /**
     * @var TemplateRepository
     */
    protected $templateRepository;

    /**
     * TemplateManager constructor.
     * @param TemplateTypeRepository $templateTypeRepository
     * @param TemplatePriceRepository $templatePriceRepository
     * @param TemplateDiscountRepository $templateDiscountRepository
     * @param TemplateCategoryRepository $templateCategoryRepository
     * @param TemplateRepository $templateRepository
     */
    public function __construct(
        TemplateTypeRepository $templateTypeRepository,
        TemplatePriceRepository $templatePriceRepository,
        TemplateDiscountRepository $templateDiscountRepository,
        TemplateCategoryRepository $templateCategoryRepository,
        TemplateRepository $templateRepository
    ) {
        $this->templateTypeRepository = $templateTypeRepository;
        $this->templatePriceRepository = $templatePriceRepository;
        $this->templateDiscountRepository = $templateDiscountRepository;
        $this->templateCategoryRepository = $templateCategoryRepository;
        $this->templateRepository = $templateRepository;
    }

    /**
     * @return Collection
     */
    public function getTemplates(): Collection
    {
        return $this->templateRepository->getTemplates();
    }

    /**
     * @param int $id
     * @return Template
     */
    public function getTemplateById(int $id): Template
    {

        return $this->templateRepository->getById($id);
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function getTemplateCategoryById(int $id): Collection
    {
        return $this->templateCategoryRepository->getTemplateCategoryById($id);
    }

    /**
     * @param int $categoryId
     * @param int $subcategoryId
     * @return LengthAwarePaginator
     */
    public function getTemplateWithCategoryAndSub(int $categoryId, ?int $subcategoryId): LengthAwarePaginator
    {
        return $this->templateRepository->getTemplateWithCategoryAndSub($categoryId, $subcategoryId);
    }

    /**
     * @return Collection
     */
    public function getTemplateTypes(): Collection
    {
        return Cache::remember('template_types', Carbon::now()->addMinutes(1), function () {
            return $this->templateTypeRepository->getAll();
        });
    }

    /**
     * @return Collection
     */
    public function getAllTemplateCategories(): Collection
    {
        return $this->templateCategoryRepository->getAllTemplateCategories();
    }

    /**
     * @param array $data
     * @return TemplateCategory
     */
    public function createTemplateCategories(array $data): TemplateCategory
    {
        return $this->templateCategoryRepository->createTemplateCategories($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return TemplateCategory
     */
    public function updateTemplateCategories(array $data, int $id): TemplateCategory
    {
        return $this->templateCategoryRepository->updateTemplateCategories($id, $data);
    }

    /**
     * @param int $id
     */
    public function deleteTemplateCategories(int $id)
    {
        $this->templateCategoryRepository->destroy($id);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function listTemplateCategories(): LengthAwarePaginator
    {
        return $this->templateCategoryRepository->withoutActiveScope()->getTemplateCategories();
    }

    /**
     * @return Collection
     */
    public function listTemplateCategoriesDropdowns(): Collection
    {
        return $this->templateCategoryRepository->getAll();
    }

    /**
     * @param array $data
     * @return Template
     */
    public function createTemplate(array $data): Template
    {

        $price = $data['price'];
        unset($data['price']);

        $template = $this->templateRepository->createTemplate($data);
        $this->createTemplatePrice([
            'template_id' => $template->id,
            'price' => $price,
            'active' => self::STATUS_ACTIVE
        ]);

        return $template;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Template
     */
    public function updateTemplate(array $data, int $id): Template
    {
        // delete old image
        $template = $this->templateRepository->getById($id);

        // delete old thumbnail
        if ($template && !empty($data['thumbnail'])) {
            Storage::delete($template->getRawOriginal('thumbnail'));
        }

        // delete old capture
        if ($template && !empty($data['capture'])) {
            Storage::delete($template->getRawOriginal('capture'));
        }

        $this->updateTemplatePrice([
            'template_id' => $id,
            'price' => $data['price'],
            'active' => self::STATUS_ACTIVE
        ], $id);

        unset($data['price']);
        return $this->templateRepository->updateTemplate($id, $data);
    }

    /**
     * @param int $id
     */
    public function deleteTemplate(int $id)
    {
        $this->deleteTemplatePrice($id);
        $this->templateRepository->destroy($id);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function listTemplate(): LengthAwarePaginator
    {
        return $this->templateRepository->withoutActiveScope()->listTemplate();
    }

    /**
     * @param array $data
     */
    public function createTemplatePrice(array $data)
    {
        $this->templatePriceRepository->createTemplatePrice($data);
    }

    /**
     * @param array $data
     * @param int $id
     */
    public function updateTemplatePrice(array $data, int $id)
    {
        $this->templatePriceRepository->updateTemplatePrice($data, $id);
    }

    /**
     * @param int $id
     */
    public function deleteTemplatePrice(int $id)
    {
        $this->templatePriceRepository->deleteTemplatePrice($id);
    }
}
