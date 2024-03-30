<?php

namespace App\Http\Requests\User\Auth;

use App\Http\Requests\MasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends MasterRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email'         => 'nullable|email|unique:users,email,' . auth('api')->id(),
            'name'          => 'nullable|unique:users,name,' . auth('api')->id(),
            'phone'         => 'nullable|unique:users,phone,'. auth('api')->id(),
            'dailing_code'  => 'nullable|max:255',
            'country_id'    => 'nullable|exists:countries,id',
            'city_id'       => 'nullable|exists:cities,id',
            'area_id'       => 'nullable|exists:areas,id',
            'specialty_id'  => 'nullable|exists:specialties,id',
            'education_id'  => 'nullable|exists:education,id',
            'gender'        => 'nullable|in:female,male',
            'image'         => 'nullable|file|mimes:png,jpg'
        ];
    }
}
