<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataCenterLocation extends Model
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
     * Get Data hosting plan
     */
    public function hosting()
    {
        return $this->belongsToMany(HostingPlan::class, 'hosting_dc_location_mappings', 'dc_location_id', 'hosting_plan_id');
    }
}
