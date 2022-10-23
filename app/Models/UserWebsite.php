<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserWebsite extends Model
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
    }

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_id');
    }

    /**
     * Get the template
     */
    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    /**
     * Get the hostingplan
     */
    public function hostingPlan()
    {
        return $this->belongsTo(HostingPlan::class, 'hosting_plan_id');
    }

    /**
     * Get the location
     */
    public function dcLocation()
    {
        return $this->belongsTo(DataCenterLocation::class, 'dc_location_id');
    }

    /**
     * Get the domain type
     */
    public function domainType()
    {
        return $this->belongsTo(DomainType::class, 'domain_type_id');
    }

    /**
     * Get the domain type
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    /**
     * Get the transaction
     */
    public function transaction()
    {
        return $this->hasMany(TransactionHistory::class, 'relation_id', 'id');
    }

    /**
     * Get the website messsage
     */
    public function websiteMessages()
    {
        return $this->hasMany(WebsiteMessage::class, 'user_website_id', 'id');
    }
}
