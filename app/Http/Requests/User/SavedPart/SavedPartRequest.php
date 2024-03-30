<?php

namespace App\Http\Requests\User\SavedPart;

use App\Http\Requests\MasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class SavedPartRequest extends MasterRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'content'   => 'required|string',
            'book_id'   => 'nullable|exists:books,id',
            'section_id' => 'nullable|exists:sections,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'lesson_id' => 'nullable|exists:lessons,id',
        ];
    }
}
