<?php

namespace App\Http\Requests\Admin\Setting;

use App\Http\Requests\MasterRequest;

class UpdateSettingRequest extends MasterRequest
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
            'key'    => 'nullable|string|unique:settings,key,' . $this->id,
            'value'  => 'nullable|string',
            'active' => 'nullable|boolean',
            'file'   => 'nullable|file|mimes:png,jpg',
        ];
    }
}
