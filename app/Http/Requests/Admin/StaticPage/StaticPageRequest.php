<?php

namespace App\Http\Requests\Admin\StaticPage;

use App\Http\Requests\MasterRequest;

class StaticPageRequest extends MasterRequest
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
            'key'    => 'required|string|in:about,privacy|unique:static_pages,key',
            'value'  => 'required|string',
            'active' => 'required|boolean',
            'file'   => 'nullable|file|mimes:png,jpg',
        ];
    }
}
