<?php

namespace App\Http\Requests\Admin\Notification;

use App\Http\Requests\MasterRequest;

class SendNotificationRequest extends MasterRequest
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
            'title' => 'required|string|max:255',
            'body'  => 'required|string|max:455',
            'users' => 'required|array',
            'users.*' => 'required|exists:users,id'
        ];
    }
}
