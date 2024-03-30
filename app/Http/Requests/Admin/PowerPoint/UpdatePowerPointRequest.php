<?php

namespace App\Http\Requests\Admin\PowerPoint;

use App\Http\Requests\MasterRequest;

class UpdatePowerPointRequest extends MasterRequest
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
            'title'  => 'nullable|string|unique:power_points,title,' . $this->power_point->id,
            'body'   => 'nullable|string',
            'file'   => 'nullable|file|mimes:ppt,pptx,pdf',
            'pdf_file' => 'nullable|file|mimes:pdf',
            'active' => 'nullable|boolean',
            'article_category_id' => 'nullable|exists:article_categories,id'
        ];
    }
}
