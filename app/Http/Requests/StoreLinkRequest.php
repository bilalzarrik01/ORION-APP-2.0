<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Link;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Link::class);
    }

    public function rules(): array
    {
        $user = $this->user();

        $categoryExistsRule = Rule::exists('categories', 'id');
        if ($user && ! $user->isAdmin()) {
            $categoryExistsRule = $categoryExistsRule->where('user_id', $user->id);
        }

        return [
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'category_id' => ['required', $categoryExistsRule],
            'tags' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre du lien est obligatoire.',
            'url.required' => 'L URL est obligatoire.',
            'url.url' => 'Veuillez saisir une URL valide.',
            'category_id.required' => 'Veuillez choisir une categorie.',
            'category_id.exists' => 'La categorie selectionnee est invalide.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $url = $this->input('url');
            $categoryId = $this->integer('category_id');

            if (! $url || ! $categoryId) {
                return;
            }

            $ownerId = Category::query()->whereKey($categoryId)->value('user_id');
            if (! $ownerId) {
                return;
            }

            $alreadyExists = Link::query()
                ->where('url', $url)
                ->whereHas('category', fn ($query) => $query->where('user_id', $ownerId))
                ->exists();

            if ($alreadyExists) {
                $validator->errors()->add('url', 'Cette URL existe deja dans vos ressources.');
            }
        });
    }
}
