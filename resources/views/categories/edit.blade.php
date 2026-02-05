<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-muted">Collections</p>
            <h2 class="mt-2 font-display text-4xl">Edit category</h2>
            <p class="mt-3 max-w-xl text-sm text-muted">Update the name for this collection.</p>
        </div>
    </x-slot>

    <div class="mt-8 panel">
        <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-input-label class="text-muted" for="name" value="Category name" />
                <x-text-input id="name" name="name" class="auth-input" type="text" :value="old('name', $category->name)" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a class="btn btn-secondary" href="{{ route('categories.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
