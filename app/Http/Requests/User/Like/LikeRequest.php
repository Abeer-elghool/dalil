<?php

namespace App\Http\Requests\User\Like;

use App\Http\Requests\MasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class LikeRequest extends MasterRequest
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
            'likeable_type' => 'required|in:section,chapter,lesson,article,lecture,photo,powerPoint,protocol,mcq,LatestNews',
            'likeable_id'   => 'required|integer',
            'type'          => 'required|in:like,dislike'
        ];
    }
}
