<?php

namespace App\Http\Requests\User\Auth;

use App\Http\Requests\MasterRequest;

class RegisterRequest extends MasterRequest
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
            'email'         => 'required|email|unique:users|min:9|max:255',
            'name'          => 'required|unique:users|min:9|max:255',
            'phone'         => 'nullable|unique:users|min:9|max:255',
            'dailing_code'  => 'nullable|max:255',
            'country_id'    => 'required|exists:countries,id',
            'city_id'       => 'required|exists:cities,id',
            'area_id'       => 'nullable|exists:areas,id',
            'specialty_id'  => 'required|exists:specialties,id',
            'education_id'  => 'required|exists:education,id',
            'gender'        => 'required|in:female,male',
            'image'         => 'nullable|file|mimes:png,jpg',
            'password'      => 'required|min:6|max:255|confirmed'
        ];
    }
}
