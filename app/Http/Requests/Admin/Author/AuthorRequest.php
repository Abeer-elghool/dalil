<?php

namespace App\Http\Requests\Admin\Author;

use App\Http\Requests\MasterRequest;

class AuthorRequest extends MasterRequest
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
            'name'     => 'required|string',
            'position' => 'required|string',
            'about'    => 'required|string',
            'file'     => 'required|file|mimes:png,jpg',
            'active'   => 'required|boolean'
        ];
    }
}
