<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-muted">Library</p>
            <h2 class="mt-2 font-display text-4xl">Edit link</h2>
            <p class="mt-3 max-w-xl text-sm text-muted">Refine the link details and tags.</p>
        </div>
    </x-slot>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <div class="panel">
            <form method="POST" action="{{ route('links.update', $link) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label class="text-muted" for="title" value="Title" />
                    <x-text-input id="title" name="title" class="auth-input" type="text" :value="old('title', $link->title)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                <div>
                    <x-input-label class="text-muted" for="url" value="URL" />
                    <x-text-input id="url" name="url" class="auth-input" type="url" :value="old('url', $link->url)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('url')" />
                </div>

                @if ($isOwner)
                    <div>
                        <x-input-label class="text-muted" for="category_id" value="Category" />
                        <select id="category_id" name="category_id" class="field-input">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $link->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                    </div>
                @else
                    <div class="text-sm text-muted">
                        Category: {{ $link->category?->name }}
                    </div>
                @endif

                <div>
                    <x-input-label class="text-muted" for="tags" value="Tags (comma separated)" />
                    <x-text-input id="tags" name="tags" class="auth-input" type="text" :value="old('tags', $tags)" placeholder="design, product, research" />
                    <x-input-error class="mt-2" :messages="$errors->get('tags')" />
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a class="btn btn-secondary" href="{{ route('links.index') }}">Cancel</a>
                </div>
            </form>
        </div>

        @if ($canShare)
            <div class="panel">
                <h3 class="font-display text-2xl">Share link</h3>

                <form method="POST" action="{{ route('links.share.store', $link) }}" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <x-input-label class="text-muted" for="user_id" value="User" />
                        <select id="user_id" name="user_id" class="field-input">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                    </div>
                    <div>
                        <x-input-label class="text-muted" for="permission" value="Permission" />
                        <select id="permission" name="permission" class="field-input">
                            <option value="lecture">Lecture</option>
                            <option value="edition">Edition</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('permission')" />
                    </div>
                    <button type="submit" class="btn btn-primary">Share</button>
                </form>

                <div class="mt-6 space-y-3">
                    @forelse ($shares as $share)
                        <div class="panel-soft">
                            <p class="text-sm font-semibold">{{ $share->name }}</p>
                            <p class="text-xs text-muted">{{ $share->email }}</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <form method="POST" action="{{ route('links.share.update', [$link, $share]) }}" class="flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="permission" class="field-input">
                                        <option value="lecture" @selected($share->pivot->permission === 'lecture')>Lecture</option>
                                        <option value="edition" @selected($share->pivot->permission === 'edition')>Edition</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">Update</button>
                                </form>
                                <form method="POST" action="{{ route('links.share.destroy', [$link, $share]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary">Remove</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-muted">No shared users yet.</p>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

