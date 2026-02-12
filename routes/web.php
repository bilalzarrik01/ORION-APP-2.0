<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\LinkShareController;
use App\Http\Controllers\LinkTrashController;
use App\Http\Controllers\ProfileController;
use App\Models\Link;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $userId = Auth::id();

    $linksQuery = Link::query()
        ->whereHas('category', fn ($query) => $query->where('user_id', $userId));

    $linksThisWeek = (clone $linksQuery)
        ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
        ->count();

    $tagsCount = Tag::query()
        ->whereHas('links.category', fn ($query) => $query->where('user_id', $userId))
        ->distinct('tags.id')
        ->count('tags.id');

    $datesWithLinks = (clone $linksQuery)
        ->select(DB::raw('date(created_at) as link_date'))
        ->distinct()
        ->orderByDesc('link_date')
        ->pluck('link_date')
        ->map(fn ($date) => Carbon::parse($date)->toDateString())
        ->values();

    $streakDays = 0;
    $cursor = Carbon::today();
    $datesSet = $datesWithLinks->flip();

    while ($datesSet->has($cursor->toDateString())) {
        $streakDays++;
        $cursor->subDay();
    }

    $recentLinks = (clone $linksQuery)
        ->with('tags')
        ->latest()
        ->take(3)
        ->get();

    return view('dashboard', compact('linksThisWeek', 'tagsCount', 'streakDays', 'recentLinks'));
})->middleware(['auth', 'verified', 'active'])->name('dashboard');

Route::middleware(['auth', 'active'])->group(function () {
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('links', LinkController::class)->except(['show']);
    Route::get('/links-trash', [LinkTrashController::class, 'index'])->name('links.trash');
    Route::post('/links-trash/{id}/restore', [LinkTrashController::class, 'restore'])->name('links.restore');
    Route::delete('/links-trash/{id}/force-delete', [LinkTrashController::class, 'forceDelete'])->name('links.force-delete');

    Route::post('/links/{link}/share', [LinkShareController::class, 'store'])->name('links.share.store');
    Route::patch('/links/{link}/share/{user}', [LinkShareController::class, 'update'])->name('links.share.update');
    Route::delete('/links/{link}/share/{user}', [LinkShareController::class, 'destroy'])->name('links.share.destroy');

    Route::post('/links/{link}/favorite', [FavoriteController::class, 'store'])->name('links.favorite.store');
    Route::delete('/links/{link}/favorite', [FavoriteController::class, 'destroy'])->name('links.favorite.destroy');

    Route::middleware('role:admin')->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
