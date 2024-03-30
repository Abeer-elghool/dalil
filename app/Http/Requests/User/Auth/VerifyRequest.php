<?php

namespace App\Http\Requests\User\Auth;

use App\Http\Requests\MasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends MasterRequest
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
            'email'         => 'required|exists:users,email',
            'verified_code' => 'required'
        ];
    }
}
