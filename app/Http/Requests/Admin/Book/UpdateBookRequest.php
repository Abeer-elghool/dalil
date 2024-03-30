<?php

namespace App\Http\Requests\Admin\Book;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\MasterRequest;

class UpdateBookRequest extends MasterRequest
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

            'title' => 'required|string|max:255|unique:books,title,' . $this->id,
            'desc' => 'required|string',
            'active' => 'nullable|boolean',
            'file' => 'nullable|file|mimes:png,jpg,pdf',
        ];
    }
}
