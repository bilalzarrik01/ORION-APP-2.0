<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Link;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('link'));
    }

    public function rules(): array
    {
        /** @var \App\Models\Link $link */
        $link = $this->route('link');
        $user = $this->user();

        $categoryRules = ['prohibited'];
        if ($link && $user && ($user->isAdmin() || $link->isOwnedBy($user))) {
            $categoryExistsRule = Rule::exists('categories', 'id');
            if (! $user->isAdmin()) {
                $categoryExistsRule = $categoryExistsRule->where('user_id', $user->id);
            }

            $categoryRules = [
                'required',
                $categoryExistsRule,
            ];
        }

        return [
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'category_id' => $categoryRules,
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
            'category_id.prohibited' => 'Vous ne pouvez pas modifier la categorie de ce lien partage.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $url = $this->input('url');
            /** @var \App\Models\Link $link */
            $link = $this->route('link');

            if (! $url || ! $link) {
                return;
            }

            $ownerId = null;
            $selectedCategoryId = $this->integer('category_id');
            if ($selectedCategoryId) {
                $ownerId = Category::query()->whereKey($selectedCategoryId)->value('user_id');
            }

            if (! $ownerId) {
                $ownerId = $link->category()->value('user_id');
            }

            if (! $ownerId) {
                return;
            }

            $alreadyExists = Link::query()
                ->where('url', $url)
                ->where('id', '!=', $link->id)
                ->whereHas('category', fn ($query) => $query->where('user_id', $ownerId))
                ->exists();

            if ($alreadyExists) {
                $validator->errors()->add('url', 'Cette URL existe deja dans vos ressources.');
            }
        });
    }
}
