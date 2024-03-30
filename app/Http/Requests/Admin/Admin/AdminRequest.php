<?php

namespace App\Http\Requests\Admin\Admin;

use App\Http\Requests\MasterRequest;

class AdminRequest extends MasterRequest
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
            'name'     => 'required|string|max:45',
            'image'    => 'nullable|file|mimes:png,jpg',
            'phone'    => 'required|unique:admins,phone|min:6',
            'email'    => 'required|email|unique:admins,email',
            'gender'   => 'required|in:female,male',
            'active'   => 'required|boolean',
            'password' => 'required|min:6|confirmed'
        ];
    }
}
