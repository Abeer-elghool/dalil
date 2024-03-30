<?php

namespace App\Http\Requests\Admin\Mcq;

use App\Http\Requests\MasterRequest;

class UpdateMcqRequest extends MasterRequest
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
            'title'             => 'nullable|string|unique:mcqs,title,' . $this->mcq->id,
            'file'              => 'nullable|file|mimes:png,jpg,pdf',
            'duration'          => 'nullable|numeric|min:0',
            'question_score'    => 'nullable|numeric|min:1',
        ];
    }
}
