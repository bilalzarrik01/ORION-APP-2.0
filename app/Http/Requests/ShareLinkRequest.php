<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShareLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('share', $this->route('link'));
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id', Rule::notIn([$this->user()->id])],
            'permission' => ['required', Rule::in(['lecture', 'edition'])],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Veuillez selectionner un utilisateur.',
            'user_id.exists' => 'L utilisateur selectionne est invalide.',
            'user_id.not_in' => 'Vous ne pouvez pas partager un lien avec vous-meme.',
            'permission.required' => 'Le niveau d acces est obligatoire.',
            'permission.in' => 'La permission doit etre lecture ou edition.',
        ];
    }
}
