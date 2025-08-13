<?php

namespace App\Http\Requests\OutgoingLetter;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOutgoingLetterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update-outgoing-letter');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'letter_number' => ['required', 'string', 'max:255', Rule::unique('letters', 'letter_number')->ignore($this->outgoing_letter->id)],
            'letter_date' => ['required', 'date', 'before_or_equal:today'],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'subject' => ['required', 'string', 'max:255'],
            'recipient' => ['required', 'string', 'max:255'],
            'attachment_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }
}
