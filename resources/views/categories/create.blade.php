<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-muted">Collections</p>
            <h2 class="mt-2 font-display text-4xl">Create category</h2>
            <p class="mt-3 max-w-xl text-sm text-muted">Give the collection a clear name.</p>
        </div>
    </x-slot>

    <div class="mt-8 panel">
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-6">
            @csrf

            <div>
                <x-input-label class="text-muted" for="name" value="Category name" />
                <x-text-input id="name" name="name" class="auth-input" type="text" :value="old('name')" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-secondary" href="{{ route('categories.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
