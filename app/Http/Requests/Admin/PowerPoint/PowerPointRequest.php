<?php

namespace App\Http\Requests\Admin\PowerPoint;

use App\Http\Requests\MasterRequest;

class PowerPointRequest extends MasterRequest
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
            'title'  => 'required|string|unique:power_points,title',
            'desc'   => 'nullable|string',
            'link'   => 'nullable|string',
            'file'   => 'required|file|mimes:ppt,pptx,pdf',
            'pdf_file' => 'nullable|file|mimes:pdf',
            'active' => 'required|boolean',
        ];
    }
}
