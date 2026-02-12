<?php

namespace App\Http\Controllers;

use App\Events\LinkActionOccurred;
use App\Models\Link;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LinkTrashController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $query = Link::onlyTrashed()->with('category');
        if (! $user->isAdmin()) {
            $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('user_id', $user->id));
        }

        $links = $query->latest('deleted_at')->get();

        return view('links.trash', compact('links'));
    }

    public function restore(string $id): RedirectResponse
    {
        $link = Link::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $link);

        $link->restore();
        event(new LinkActionOccurred(Auth::user(), $link, 'restored'));

        return redirect()->route('links.trash')->with('status', 'Lien restaure.');
    }

    public function forceDelete(string $id): RedirectResponse
    {
        $link = Link::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $link);

        $link->forceDelete();
        event(new LinkActionOccurred(Auth::user(), $link, 'force_deleted'));

        return redirect()->route('links.trash')->with('status', 'Lien supprime definitivement.');
    }
}

