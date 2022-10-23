<?php

namespace App\Http\Requests\Domain;

use Illuminate\Foundation\Http\FormRequest;

class CreateDomainRequest extends FormRequest
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
            'domain_name' => 'required|regex:/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})*$/|max:30',
            'domain_time' => 'required|integer|min:1|max:3',
            'domain_type_id' => 'required|integer|exists:domain_types,id',
            'total_price' => 'nullable|numeric|min:0',
        ];
    }
}
