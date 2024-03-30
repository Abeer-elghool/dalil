<?php

namespace App\Http\Requests\Admin\LatestNews;

use App\Http\Requests\MasterRequest;

class UpdateLatestNewsRequest extends MasterRequest
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
            'title'  => 'nullable|string|unique:latest_news,title,'. $this->latest_news->id,
            'desc'   => 'nullable|string',
            'author_name' => 'required|string',
            'file'   => 'nullable|file|mimes:png,jpg,pdf',
            'active' => 'nullable|boolean'
        ];
    }
}
