<?php

namespace App\Http\Requests\TemporaryDomain;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTemporaryDomainRequest extends FormRequest
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
            'domain_name' => 'required|regex:/^((?!-)[a-z0-9-]{0,63}[a-z0-9]\.)+[a-z]{2,63}$/|max:30',
            'ip' => 'required|string|max:255'
        ];
    }
}
