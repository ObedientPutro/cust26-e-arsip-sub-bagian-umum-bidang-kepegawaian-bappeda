<?php

namespace App\Http\Requests\IncomingLetter;

use App\Enums\LetterTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreIncomingLetterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create-incoming-letter');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'letter_number' => ['required', 'string', 'max:255', Rule::unique('letters', 'letter_number')],
            'subject' => ['required', 'string', 'max:255'],
            'letter_date' => ['required', 'date', 'before_or_equal:today'],
            'sender' => ['required', 'string', 'max:255'],
            'attachment_file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }
}
