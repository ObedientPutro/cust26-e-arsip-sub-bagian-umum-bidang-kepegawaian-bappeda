<?php

namespace App\Http\Requests\Disposition;

use Illuminate\Foundation\Http\FormRequest;

class StoreDispositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create-disposition');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'instruction' => ['required', 'string', 'min:5'],
            'recipients' => ['required', 'array', 'min:1'],
            'recipients.*' => ['exists:users,id'],
        ];
    }

    /**
     * Memberikan pesan error kustom (opsional).
     */
    public function messages(): array
    {
        return [
            'recipients.required' => 'Anda harus memilih minimal satu penerima disposisi.',
            'instruction.required' => 'Instruksi disposisi tidak boleh kosong.',
        ];
    }
}
