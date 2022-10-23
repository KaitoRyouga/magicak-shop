<?php

namespace App\Http\Requests\UserWebsite;

use Illuminate\Foundation\Http\FormRequest;

class CheckBusinessNameExistRequest extends FormRequest
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
            'business_name' => 'required|regex:/^[a-zA-Z_][a-zA-Z0-9_]*$/i|max:30'
        ];
    }
}
