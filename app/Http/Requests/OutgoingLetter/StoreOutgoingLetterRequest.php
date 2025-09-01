<?php

namespace App\Http\Requests\OutgoingLetter;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOutgoingLetterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'is_archive_mode' => ['required', 'boolean'],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'subject' => ['required', 'string', 'max:255'],
            'recipient' => ['required', 'string', 'max:255'],
            'attachment_file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ];

        if ($this->input('is_archive_mode')) {
            $rules['letter_number'] = ['required', 'string', 'max:255', Rule::unique('letters', 'letter_number')];
            $rules['letter_date'] = ['required', 'date', 'before_or_equal:today'];
        }

        return $rules;
    }
}
