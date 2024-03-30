<?php

namespace App\Http\Requests\Admin\Mcq;

use App\Http\Requests\MasterRequest;

class McqRequest extends MasterRequest
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
            'title'             => 'required|string|unique:mcqs,title',
            'file'              => 'nullable|file|mimes:png,jpg,pdf',
            'questions_count'   => 'required|integer|min:0',
            'duration'          => 'nullable|numeric|min:0',
            'question_score'    => 'required|numeric|min:1',
            'questions_type'    => 'required|in:random,specified',
            'mcq_sections'      => 'nullable|in:section,chapter|required_if:questions_type,specified',
            'sections'          => 'required_if:mcq_sections,section|array',
            'sections.*.id'     => 'exists:sections,id',
            'sections.*.questions_count' => 'required_with:sections.*.id',
            'chapters'          => 'required_if:mcq_sections,chapter|array',
            'chapters.*.id'     => 'exists:chapters,id',
            'chapters.*.questions_count' => 'required_with:chapters.*.id'
        ];
    }
}
