<?php

namespace App\Http\Requests\IncomingLetter;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIncomingLetterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update-incoming-letter');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'letter_number' => ['required', 'string', 'max:255', Rule::unique('letters', 'letter_number')->ignore($this->incoming_letter->id)],
            'subject' => ['required', 'string', 'max:255'],
            'letter_date' => ['required', 'date', 'before_or_equal:today'],
            'sender' => ['required', 'string', 'max:255'],
            'attachment_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }
}
