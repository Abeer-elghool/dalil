<?php

namespace App\Http\Requests\Admin\Admin;

use App\Http\Requests\MasterRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends MasterRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'nullable|string|max:45',
            'image'    => 'nullable|file|mimes:png,jpg',
            'phone'    => ['nullable', Rule::unique('admins')->ignore($this->admin)],
            'email'    => ['nullable', 'email', Rule::unique('admins')->ignore($this->admin)],
            'gender'   => 'nullable|in:female,male',
            'active'   => 'nullable|boolean',
            'password' => 'nullable|min:6|confirmed'
        ];
    }
}
