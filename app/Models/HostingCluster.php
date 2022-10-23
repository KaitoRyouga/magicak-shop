<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HostingCluster extends Model
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
     * Get the location
     */
    public function dcLocation()
    {
        return $this->belongsTo(DataCenterLocation::class, 'dc_location_id');
    }

    /**
     * Get systemDomain
     */
    public function systemDomain()
    {
        return $this->belongsTo(SystemDomain::class, 'system_domain_id');
    }

    /**
     * Get hosting platform
     */
    public function platform()
    {
        return $this->belongsTo(HostingPlatform::class, 'hosting_platform_id');
    }
}
