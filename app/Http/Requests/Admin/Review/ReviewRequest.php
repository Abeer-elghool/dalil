<?php

namespace App\Http\Requests\Admin\Review;

use App\Http\Requests\MasterRequest;

class ReviewRequest extends MasterRequest
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
            'title'             => 'required|string',
            'desc'              => 'required|string',
            'author_name'       => 'required|string',
            'author_position'   => 'required|string',
            'active'            => 'required|boolean',
            'file'              => 'nullable|file|mimes:png,jpg,pdf',
        ];
    }
}
