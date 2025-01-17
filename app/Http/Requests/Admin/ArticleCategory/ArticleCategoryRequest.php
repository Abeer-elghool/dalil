<?php

namespace App\Http\Requests\Admin\ArticleCategory;

use App\Http\Requests\MasterRequest;

class ArticleCategoryRequest extends MasterRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'desc'  => 'nullable|string',
            'file'  => 'nullable|file|mimes:png,jpg,pdf',
            'active'=>'nullable|boolean'
        ];
    }
}
