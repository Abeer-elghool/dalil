<?php

namespace App\Http\Requests\User\Auth;

use App\Http\Requests\MasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends MasterRequest
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
            'email'      => 'required|exists:users,email',
            'reset_code' => 'required|string',
            'password'   => 'required|min:6|confirmed'
        ];
    }
}
