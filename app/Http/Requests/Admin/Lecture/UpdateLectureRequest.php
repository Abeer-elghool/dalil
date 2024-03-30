<?php

namespace App\Http\Requests\Admin\Lecture;

use App\Http\Requests\MasterRequest;

class UpdateLectureRequest extends MasterRequest
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
            'title'  => 'nullable|string|unique:lectures,title,' . $this->lecture->id,
            'desc'   => 'nullable|string',
            'file'   => 'nullable||mimetypes:video/mp4,video/mpeg,video/quicktime',
            'active' => 'nullable|boolean'
        ];
    }
}
