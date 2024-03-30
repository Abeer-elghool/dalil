<?php

namespace App\Http\Requests\Admin\Lesson;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\MasterRequest;

class LessonRequest extends MasterRequest
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
            // 'title'=>'required|unique:lessons,title|string|max:255',
            'title'=>'required|string|max:255',
            'desc'=>'required|string',
            'active'=>'nullable|boolean',
            'file'=>'nullable|file|mimes:png,jpg,pdf',
            'chapter_id'=>'required|exists:chapters,id',

        ];
    }
}
