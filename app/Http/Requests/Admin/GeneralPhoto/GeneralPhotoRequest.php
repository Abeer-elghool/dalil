<?php

namespace App\Http\Requests\Admin\GeneralPhoto;

use App\Http\Requests\MasterRequest;

class GeneralPhotoRequest extends MasterRequest
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
            'file'   => 'nullable|file|mimes:png,jpg',
            'upload'=>'nullable|file|mimes:png,jpg',
        ];
    }
}
