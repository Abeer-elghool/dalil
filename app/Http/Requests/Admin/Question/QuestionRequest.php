<?php

namespace App\Http\Requests\Admin\Question;

use App\Http\Requests\MasterRequest;

class QuestionRequest extends MasterRequest
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
            'title'                 => 'required|string',
            'file'                  => 'nullable|file|mimes:png,jpg,pdf',
            'answers'               => 'required|array|min:2',
            'answers.*'             => 'required|string',
            'correct_answer_index'  => 'required|integer|between:0,' . ($this->answers ? count($this->answers) - 1 : 4),
            'related_to'            => 'required|in:section,chapter,lesson',
            'section_id'            => 'nullable|required_if:related_to,section|exists:sections,id',
            'chapter_id'            => 'nullable|required_if:related_to,chapter|exists:chapters,id',
            'lesson_id'             => 'nullable|required_if:related_to,lesson|exists:lessons,id'
        ];
    }
}
