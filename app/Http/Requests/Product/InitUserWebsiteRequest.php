<?php

namespace App\Http\Requests\Product;

use App\Managers\ProductManager;
use Illuminate\Foundation\Http\FormRequest;

class InitProductRequest extends FormRequest
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
            'status' => 'nullable|string|in:' . implode(',', ProductManager::STATUSES),
            'current_tasks' => 'string',
            'hosting_created_date' => 'date',
            'hosting_expired_date' => 'date',
            'website_url' => 'string',
            'hosting_ip' => 'string',
            'system_message' => 'string',
            'deleted_at' => 'date',
            'deleted_id' => 'integer'
        ];
    }
}
