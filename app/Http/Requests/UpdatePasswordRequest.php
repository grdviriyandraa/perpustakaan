<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('anggota')->check();
    }

    public function rules(): array
    {
        return [
            'password_lama' => ['required', 'current_password:anggota'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    public function attributes(): array
    {
        return [
            'password_lama' => 'password lama',
            'password' => 'password baru',
        ];
    }
}
