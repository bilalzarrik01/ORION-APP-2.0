<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSharePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('share', $this->route('link'));
    }

    public function rules(): array
    {
        return [
            'permission' => ['required', Rule::in(['lecture', 'edition'])],
        ];
    }

    public function messages(): array
    {
        return [
            'permission.required' => 'Le niveau d acces est obligatoire.',
            'permission.in' => 'La permission doit etre lecture ou edition.',
        ];
    }
}

