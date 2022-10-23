<?php

namespace App\Http\Requests\HostingPlans;

use Illuminate\Foundation\Http\FormRequest;

class FilterHostingPlansRequest extends FormRequest
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
            'location_id' => 'required|integer',
            'hosting_type_id' => 'required|integer'
        ];
    }
}
