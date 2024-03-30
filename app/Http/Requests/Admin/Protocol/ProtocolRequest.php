<?php

namespace App\Http\Requests\Admin\Protocol;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\MasterRequest;

class ProtocolRequest extends MasterRequest
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
            'title'=>'required|string|max:255',
            'desc'=>'required|string',
            'active'=>'nullable|boolean',
            'file'=>'nullable|file|mimes:png,jpg,pdf',
            'book_id'=>'nullable|exists:books,id',
            'protocol_category_id'=>'required|exists:protocol_categories,id',
            'section_id'=>'nullable|exists:sections,id',
            'chapter_id'=>'nullable|exists:chapters,id',
            'lesson_id'=>'nullable|exists:lessons,id'
        ];
    }
}
