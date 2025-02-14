<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'     => 'required|string|email|max:255',
            'password'  => 'required|string|min:8',
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'        => 'Email is required.',
            'email.string'          => 'Please provide a valid email format.',
            'email.email'           => 'Please provide a valid email address.',
            'password.required'     => 'Password is required.',
            'password.min'          => 'Password must be at least 8 characters.'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'  => 400,
            'message' => 'Validation failed.',
            'error'  => $validator->errors(),
            'data'    => null,
        ], 400));
    }
}
