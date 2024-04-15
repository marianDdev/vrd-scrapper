<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'name'         => ['nullable', 'string', 'min:2'],
            //'website'      => ['nullable', 'regex:/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/'],
            'website'      => ['nullable'],
            'phone_number' => ['nullable', 'string'],
            'facebook'     => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'website.regex' => "The website field format is invalid. Use this format instead: 'example.com', without any prefix.",
        ];
    }
}
