<?php

namespace App\Http\Requests\Domain;

use Illuminate\Foundation\Http\FormRequest;

class CheckDomainNameExistRequest extends FormRequest
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
            'domain_name' => 'required|regex:/^([a-z0-9][a-z0-9-_]*\.)*[a-z0-9]*[a-z0-9-_]*[[a-z0-9]+$/|max:30',
            'domain_type_id' => 'required|integer|exists:domain_types,id',
        ];
    }
}
