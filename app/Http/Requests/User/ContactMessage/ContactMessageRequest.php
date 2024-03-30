<?php

namespace App\Http\Requests\User\ContactMessage;

use App\Http\Requests\MasterRequest;

class ContactMessageRequest extends MasterRequest
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
            'name'    => auth('api')->check() ? 'nullable|string|max:45' : 'required|string|max:45',
            'email'   => auth('api')->check() ? 'nullable|email|max:255' : 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:500',
        ];
    }
}
