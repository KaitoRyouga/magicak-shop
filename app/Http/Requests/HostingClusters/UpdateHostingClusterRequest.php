<?php

namespace App\Http\Requests\HostingClusters;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHostingClusterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'active' => 'boolean',
            'dc_location_id' => 'required|exists:data_center_locations,id',
            'hosting_platform_id' => 'required|exists:hosting_platforms,id',
            'system_domain_id' => 'required|exists:system_domains,id'
        ];
    }
}
