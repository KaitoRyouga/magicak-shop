<?php

namespace App\Http\Requests\HostingPlans;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHostingPlansRequest extends FormRequest
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
            'plan_type_id' => 'required|integer|exists:hosting_plan_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ssl' => 'required|integer|in:0,1',
            'ram' => 'required|integer',
            'cpu' => 'required|integer',
            'hosting_app_storage' => 'required|integer',
            'hosting_db_storage' => 'required|integer',
            'backup' => 'required|integer|in:0,1',
            'duration' => 'required|integer',
            'price' => 'required|integer',
            'hosting_cluster_ids' => 'required|array',
            'active' => 'required|integer|in:0,1'
        ];
    }
}
