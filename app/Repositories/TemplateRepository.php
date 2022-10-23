<?php

namespace App\Repositories;

use App\Models\Template;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class TemplateRepository extends BaseRepository
{
    /**
     * TemplateRepository constructor.
     * @param Template $template
     */
    public function __construct(Template $template)
    {
        $this->model = $template;
    }

    /**
     * @return Collection
     */
    public function getTemplates(): Collection
    {
        return $this->model->where('created_id', auth()->id())->get();
    }

    /**
     * @param int $categoryId
     * @param int $subcategoryId
     * @return LengthAwarePaginator
     */
    public function getTemplateWithCategoryAndSub(int $categoryId, ?int $subcategoryId): LengthAwarePaginator
    {
        return Cache::remember('template_with_category_' . $categoryId . 'and_subcategory_' . $subcategoryId, Carbon::now()->addMinutes(1), function () use ($categoryId, $subcategoryId) {
            $data = $this->model
                ->with([
                    'category' => function ($query) use ($categoryId) {
                        $query->select('id', 'name')->where('id', $categoryId);
                    },
                    'discounts' => function ($query) {
                        $query->where('active', 1)
                            ->where('start_time', '<=', date('Y-m-d H:i:s'))
                            ->where('end_time', '>=', date('Y-m-d H:i:s'));
                    },
                    'subcategory' => function ($query) use ($subcategoryId) {
                        $query->select('id', 'name')->where('id', $subcategoryId);
                    },
                    'prices',
                    'type'
                ])
                ->whereHas('category', function ($query) use ($categoryId) {
                    $query->where('id', $categoryId);
                });

            if ($subcategoryId) {
                $data->whereHas('subcategory', function ($query) use ($subcategoryId) {
                    $query->where('id', $subcategoryId);
                });
            }

            return $data->paginate(16);
        });
    }

    /**
     * @param array $data
     * @return Template
     */
    public function createTemplate(array $data): Template
    {
        return $this->updateOrCreate(null, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Template
     */
    public function updateTemplate(int $id, array $data): Template
    {
        return $this->updateOrCreate($id, $data);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function listTemplate(): LengthAwarePaginator
    {
        return $this->model->with('prices', 'category')->paginate();
    }
}
