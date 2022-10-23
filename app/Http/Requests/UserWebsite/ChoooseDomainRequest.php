<?php

namespace App\Http\Requests\UserWebsite;

use Illuminate\Foundation\Http\FormRequest;

class ChoooseDomainRequest extends FormRequest
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
            'domain_name' => 'required|string|max:255',
            'is_temporary' => 'required|boolean',
        ];
    }
}
