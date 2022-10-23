<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Template extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ActiveScope());
    }

    /**
     * Get the template category
     */
    public function category()
    {
        return $this->belongsToMany(TemplateCategory::class, 'template_category_mappings', 'template_id', 'category_id');
    }

    /**
     * Get the sub category
     */
    public function subcategory()
    {
        return $this->belongsToMany(TemplateSubcategory::class, 'template_subcategory_mappings', 'template_id', 'subcategory_id');
    }

    /**
     * Get template price.
     */
    public function prices()
    {
        return $this->hasMany(TemplatePrice::class, 'template_id');
    }

    /**
     * Get template discount
     */
    public function discounts()
    {
        return $this->belongsToMany(TemplateDiscount::class, 'template_discount_mappings', 'template_id', 'template_discount_id');
    }

    /**
     * Get the template type
     */
    public function type()
    {
        return $this->belongsTo(TemplateType::class, 'template_type_id');
    }

    /**
     * @param $value
     * @return string
     */
    public function getThumbnailAttribute($value): string
    {
        return Storage::url($value);
    }

    /**
     * @param $value
     * @return string
     */
    public function getCaptureAttribute($value): string
    {
        return Storage::url($value);
    }
}
