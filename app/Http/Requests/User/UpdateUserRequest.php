<?php

namespace App\Http\Requests\User;

use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Enum;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('is-admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)],
            'username' => ['required', 'string', 'lowercase', 'regex:/^\S*$/', Rule::unique('users', 'username')->ignore($this->user->id)],
            'password' => ['nullable', Rules\Password::defaults()],
            'role' => ['required', new Enum(UserRoleEnum::class)],
        ];
    }
}
