<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|min:3|max:20|unique:users',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:8',
            'quota_bytes' => 'nullable|integer|min:1048576',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Username is required',
            'username.unique' => 'Username already taken',
            'email.unique' => 'Email already registered',
            'password.confirmed' => 'Passwords do not match',
        ];
    }
}
