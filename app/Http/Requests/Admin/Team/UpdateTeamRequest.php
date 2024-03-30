<?php

namespace App\Http\Requests\Admin\Team;

use App\Http\Requests\MasterRequest;

class UpdateTeamRequest extends MasterRequest
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
            'position' => 'nullable|string',
            'about'    => 'nullable|string',
            'file'     => 'nullable|file|mimes:png,jpg',
            'active'   => 'required|boolean'
        ];
    }
}
