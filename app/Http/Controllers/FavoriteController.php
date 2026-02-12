<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Link $link): RedirectResponse
    {
        $this->authorize('view', $link);
        Auth::user()->favoriteLinks()->syncWithoutDetaching([$link->id]);

        return back()->with('status', 'Lien ajoute aux favoris.');
    }

    public function destroy(Link $link): RedirectResponse
    {
        $this->authorize('view', $link);
        Auth::user()->favoriteLinks()->detach($link->id);

        return back()->with('status', 'Lien retire des favoris.');
    }
}

