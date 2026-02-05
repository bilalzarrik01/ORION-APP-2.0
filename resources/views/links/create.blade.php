<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-muted">Library</p>
            <h2 class="mt-2 font-display text-4xl">Add link</h2>
            <p class="mt-3 max-w-xl text-sm text-muted">Save a new resource and attach tags.</p>
        </div>
    </x-slot>

    <div class="mt-8 panel">
        @if ($categories->isEmpty())
            <p class="text-sm text-muted">Create a category first to add links.</p>
            <div class="mt-4">
                <a class="btn btn-primary" href="{{ route('categories.create') }}">Create category</a>
            </div>
        @else
            <form method="POST" action="{{ route('links.store') }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label class="text-muted" for="title" value="Title" />
                    <x-text-input id="title" name="title" class="auth-input" type="text" :value="old('title')" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                <div>
                    <x-input-label class="text-muted" for="url" value="URL" />
                    <x-text-input id="url" name="url" class="auth-input" type="url" :value="old('url')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('url')" />
                </div>

                <div>
                    <x-input-label class="text-muted" for="category_id" value="Category" />
                    <select id="category_id" name="category_id" class="field-input">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                </div>

                <div>
                    <x-input-label class="text-muted" for="tags" value="Tags (comma separated)" />
                    <x-text-input id="tags" name="tags" class="auth-input" type="text" :value="old('tags')" placeholder="design, product, research" />
                    <x-input-error class="mt-2" :messages="$errors->get('tags')" />
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-secondary" href="{{ route('links.index') }}">Cancel</a>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
