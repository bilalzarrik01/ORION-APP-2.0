<?php

namespace App\Http\Controllers;

use App\Events\LinkActionOccurred;
use App\Events\LinkPermissionUpdated;
use App\Events\LinkShared;
use App\Http\Requests\ShareLinkRequest;
use App\Http\Requests\UpdateSharePermissionRequest;
use App\Models\Link;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinkShareController extends Controller
{
    public function store(ShareLinkRequest $request, Link $link): RedirectResponse
    {
        $validated = $request->validated();

        $existingShare = $link->sharedUsers()->where('users.id', $validated['user_id'])->first();
        $exists = (bool) $existingShare;
        $oldPermission = $exists ? (string) $existingShare->pivot->permission : null;

        $link->sharedUsers()->syncWithoutDetaching([
            $validated['user_id'] => ['permission' => $validated['permission']],
        ]);

        $recipient = User::findOrFail($validated['user_id']);

        if ($exists) {
            if ($oldPermission !== $validated['permission']) {
                event(new LinkPermissionUpdated(Auth::user(), $recipient, $link, $oldPermission, $validated['permission']));
                event(new LinkActionOccurred(Auth::user(), $link, 'share_permission_updated'));
            }
        } else {
            event(new LinkShared(Auth::user(), $recipient, $link, $validated['permission']));
            event(new LinkActionOccurred(Auth::user(), $link, 'shared'));
        }

        return back()->with('status', 'Partage enregistre.');
    }

    public function update(UpdateSharePermissionRequest $request, Link $link, User $user): RedirectResponse
    {
        $this->authorize('share', $link);
        $newPermission = $request->validated()['permission'];

        $shared = $link->sharedUsers()->where('users.id', $user->id)->firstOrFail();
        $oldPermission = (string) $shared->pivot->permission;

        if ($oldPermission !== $newPermission) {
            $link->sharedUsers()->updateExistingPivot($user->id, ['permission' => $newPermission]);
            event(new LinkPermissionUpdated(Auth::user(), $user, $link, $oldPermission, $newPermission));
            event(new LinkActionOccurred(Auth::user(), $link, 'share_permission_updated'));
        }

        return back()->with('status', 'Permission mise a jour.');
    }

    public function destroy(Request $request, Link $link, User $user): RedirectResponse
    {
        $this->authorize('share', $link);

        $link->sharedUsers()->detach($user->id);
        event(new LinkActionOccurred(Auth::user(), $link, 'share_revoked'));

        return back()->with('status', 'Partage supprime.');
    }
}
