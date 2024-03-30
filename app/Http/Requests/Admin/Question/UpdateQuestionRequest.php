<?php

namespace App\Http\Requests\Admin\Question;

use App\Http\Requests\MasterRequest;

class UpdateQuestionRequest extends MasterRequest
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
            'title'             => 'nullable|string',
            'file'              => 'nullable|file|mimes:png,jpg,pdf',
            'answers'           => 'nullable|array',
            'answers.*'         => 'nullable|array',
            'answers.*.id'      => 'nullable|required_with:answers.*.answer|exists:answers,id,question_id,' . $this->question->id,
            'answers.*.answer'  => 'nullable|required_with:answers.*.id|string'
        ];
    }
}
