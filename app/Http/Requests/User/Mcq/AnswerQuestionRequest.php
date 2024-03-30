<?php

namespace App\Http\Requests\User\Mcq;

use App\Http\Requests\MasterRequest;

class AnswerQuestionRequest extends MasterRequest
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
            'mcq_id'        => 'required|exists:mcqs,uuid',
            'question_id'   => 'required|exists:questions,uuid',
            'answer_id'     => 'required|exists:answers,uuid',
        ];
    }
}
