<?php

namespace App\Http\Requests\UserWebsite;

use Illuminate\Foundation\Http\FormRequest;

class UpgradePlanRequest extends FormRequest
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
            'user_website_id' => 'required|integer|exists:user_websites,id',
            'hosting_plan_id' => 'required|integer|exists:hosting_plans,id',
            'latest_purchased_package' => 'required:integer',
            'dc_location_id' => 'required|integer|exists:data_center_locations,id'

        ];
    }
}
