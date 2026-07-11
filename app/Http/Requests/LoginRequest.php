<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return ['login' => 'email atau username'];
    }

    /**
     * Kredensial untuk Auth::attempt — deteksi email vs username.
     */
    public function credentials(): array
    {
        $field = filter_var($this->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $field => $this->input('login'),
            'password' => $this->input('password'),
        ];
    }
}
