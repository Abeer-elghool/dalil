<?php

namespace App\Http\Requests\Admin\Review;

use App\Http\Requests\MasterRequest;

class UpdateReviewRequest extends MasterRequest
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
            'title'           => 'nullable|string',
            'desc'            => 'nullable|string',
            'author_name'     => 'nullable|string',
            'author_position' => 'nullable|string',
            'active'          => 'nullable|boolean',
            'file'            => 'nullable|file|mimes:png,jpg,pdf',
        ];
    }
}
