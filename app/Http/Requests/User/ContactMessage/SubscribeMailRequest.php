<?php

namespace App\Http\Requests\User\ContactMessage;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\MasterRequest;

class SubscribeMailRequest extends MasterRequest
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
            'email'   => auth('api')->check() ? 'nullable|email|max:255' : 'required|email|max:255',
        ];
    }
}
