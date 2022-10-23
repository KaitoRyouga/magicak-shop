<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HostingPlan extends Model
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
     * Get the plan type
     */
    public function type()
    {
        return $this->belongsTo(HostingPlanType::class, 'plan_type_id');
    }

    /**
     * Get the cluster
     */
    public function cluster()
    {
        return $this->belongsToMany(HostingCluster::class, 'hosting_cluster_mappings', 'hosting_plan_id', 'hosting_cluster_id');
    }

    /**
     * Get hosting price.
     */
    public function prices()
    {
        return $this->hasMany(HostingPlanPrice::class, 'hosting_plan_id');
    }

    /**
     * Get hosting discount
     */
    public function discounts()
    {
        return $this->belongsToMany(HostingPlanDiscount::class, 'hosting_discount_mappings', 'hosting_plan_id', 'hosting_discount_id');
    }

    /**
     * Get Data center location
     */
    public function dcLocation()
    {
        return $this->belongsToMany(DataCenterLocation::class, 'hosting_dc_location_mappings', 'hosting_plan_id', 'dc_location_id');
    }

    /**
     * get user website
     */
    public function userWebsite()
    {
        return $this->hasMany(UserWebsite::class, 'hosting_plan_id');
    }
}
