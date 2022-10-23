<?php

namespace App\Http\Requests\UserWebsite;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserWebsiteRequest extends FormRequest
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
            'business_name' => 'required|string|max:255',
            'template_id' => 'required|integer|exists:templates,id',
            'hosting_plan_id' => 'required|integer|exists:hosting_plans,id',
            'dc_location_id' => 'required|integer||exists:data_center_locations,id',
            'domain_type_id' => 'required|integer|exists:domain_types,id',
            'domain_name' => 'required|string',
            'domain_time' => 'required|integer|min:1|max:3',
            'total_price' => 'required|numeric|min:0',
            'domain_auth_key' => 'required_if:is_transfer,1',
            'is_transfer' => 'required_if:domain_type_id,3',
            'latest_purchased_package' => 'required:integer',
        ];
    }

}
