<?php

namespace App\Http\Requests\Admin\Photo;

use App\Http\Requests\MasterRequest;

class PhotoRequest extends MasterRequest
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
            'title'  => 'required|string|unique:photos,title',
            'desc'   => 'required|string',
            'file'   => 'required|file|mimes:png,jpg',
            'active' => 'required|boolean'
        ];
    }
}
