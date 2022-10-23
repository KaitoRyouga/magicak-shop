<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'company' => 'nullable|string',
            'email' => 'required|string|email',
            // 'role' => 'required|string',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string',
            'language' => 'nullable|string',
            'gender' => 'nullable|string',
            'twitter' => 'nullable|string',
            'facebook' => 'nullable|string',
            'instagram' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'postcode' => 'nullable|string',
            'city' => 'nullable|string',
        ];
    }
}
