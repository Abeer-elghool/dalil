<?php

namespace App\Http\Requests\Admin\Article;

use App\Http\Requests\MasterRequest;

class UpdateArticleRequest extends MasterRequest
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
            'title'  => 'nullable|string|unique:articles,title,' . $this->article->id,
            'body'   => 'nullable|string',
            'link'   => 'nullable|string',
            'file'   => 'nullable|file|mimes:png,jpg,pdf',
            'active' => 'nullable|boolean',
            'article_category_id' => 'nullable|exists:article_categories,id'
        ];
    }
}
