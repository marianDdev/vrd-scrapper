<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadWebsitesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:csv,txt'
        ];
    }

    public function messages()
    {
        return [
            'file.mimetypes' => 'The file must be a CSV file.',
            'file.mimes' => 'The file extension must be .csv',
        ];
    }
}
