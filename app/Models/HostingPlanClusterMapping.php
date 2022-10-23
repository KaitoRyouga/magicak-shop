<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostingPlanClusterMapping extends Model
{
    use HasFactory;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
    }

    /**
     * Get the hosting platform
     */
    public function systemDomain()
    {
        return $this->belongsToMany(SystemDomain::class, 'hosting_cluster_mappings', 'hosting_cluster_id', 'system_domain_id');
    }

    /**
     * Get hosting platform
     */
    public function platform()
    {
        return $this->belongsToMany(HostingPlatform::class, 'hosting_cluster_mappings', 'hosting_cluster_id', 'hosting_platform_id');
    }
}
