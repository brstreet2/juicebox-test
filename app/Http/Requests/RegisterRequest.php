<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'name'      => 'required|regex:/(^[A-Za-z0-9_-_ ]+$)+/',
            'email'     => 'required|string|email|unique:users,email|max:255',
            'password'  => 'required|string|min:8|confirmed',
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
            'name.required'         => 'Name is required.',
            'name.regex'            => 'Please provide a valid name.',
            'email.required'        => 'Email is required.',
            'email.string'          => 'Please provide a valid email format.',
            'email.email'           => 'Please provide a valid email address.',
            'email.unique'          => 'Email has been taken.',
            'password.required'     => 'Password is required.',
            'password.confirmed'    => 'Password confirmation does not match.',
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
