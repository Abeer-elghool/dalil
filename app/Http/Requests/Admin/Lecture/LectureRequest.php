<?php

namespace App\Http\Requests\Admin\Lecture;

use App\Http\Requests\MasterRequest;

class LectureRequest extends MasterRequest
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
            'title'  => 'required|string|unique:lectures,title',
            'desc'   => 'required|string',
            'file'   => 'required||mimetypes:video/mp4,video/mpeg,video/quicktime',
            'active' => 'required|boolean'
        ];
    }
}
