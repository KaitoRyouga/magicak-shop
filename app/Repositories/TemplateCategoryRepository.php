<?php

namespace App\Repositories;

use App\Models\TemplateCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class TemplateCategoryRepository extends BaseRepository
{
    /**
     * TemplateCategoryRepository constructor.
     * @param TemplateCategory $templateCategory
     */
    public function __construct(TemplateCategory $templateCategory)
    {
        $this->model = $templateCategory;
    }

    /**
     * @param $id
     * @return Collection
     */
    public function getTemplateCategoryById($id): Collection
    {
        return Cache::remember('template_category_by_id_' . $id, Carbon::now()->addMinutes(1), function () use ($id) {
            return $this->model
                ->where('id', $id)
                ->get();
        });
    }

    /**
     * @return Collection
     */
    public function getAllTemplateCategories(): Collection
    {
        return Cache::remember('template_categories', Carbon::now()->addMinutes(1), function () {
            return $this->model->get();
        });
    }

    /**
     * @param array $data
     * @return TemplateCategory
     */
    public function createTemplateCategories(array $data): TemplateCategory
    {
        return $this->updateOrCreate(null, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return TemplateCategory
     */
    public function updateTemplateCategories(int $id, array $data): TemplateCategory
    {
        return $this->updateOrCreate($id, $data);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getTemplateCategories(): LengthAwarePaginator
    {
        return $this->model->paginate();
    }
}
