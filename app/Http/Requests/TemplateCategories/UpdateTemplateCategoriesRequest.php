<?php

namespace App\Http\Requests\TemplateCategories;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTemplateCategoriesRequest extends FormRequest
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
            'template_type_id' => 'required|integer|exists:template_types,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sort' => 'nullable|integer',
            'active' => 'boolean',
        ];
    }
}
