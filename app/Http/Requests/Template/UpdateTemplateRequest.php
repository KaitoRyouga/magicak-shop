<?php

namespace App\Http\Requests\Template;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTemplateRequest extends FormRequest
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
            'template_category_id' => 'required|integer|exists:template_categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'description' => 'required|string',
            'version' => 'required|string|max:255',
            'thumbnail' => 'image|max:3000',
            'capture' => 'image|max:3000',
            'url' => 'required|string|max:255',
            'price' => 'required|numeric',
            'template_app_storage' => 'required|integer',
            'template_db_storage' => 'required|integer',
            'active' => 'boolean'
        ];
    }
}
