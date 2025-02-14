<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostRequest extends FormRequest
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
            'title'         => 'required|string|max:255',
            'description'   => 'required|string|max:255',
            'content'       => 'required|string'
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
            'title.required'        => 'Title is required.',
            'title.string'          => 'Please provide a valid string format.',
            'description.required'  => 'Description is required.',
            'description.string'    => 'Please provide a valid string format.',
            'content.required'      => 'Content is required.',
            'content.string'        => 'Content must be string.'
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
