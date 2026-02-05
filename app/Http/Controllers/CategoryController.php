<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->where('user_id', Auth::id())
            ->withCount('links')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Category::create([
            'name' => $validated['name'],
            'user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('categories.index')
            ->with('status', 'Category created.');
    }

    public function edit(string $id)
    {
        $category = $this->findUserCategory($id);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $category = $this->findUserCategory($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('status', 'Category updated.');
    }

    public function destroy(string $id)
    {
        $category = $this->findUserCategory($id);
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('status', 'Category deleted.');
    }

    private function findUserCategory(string $id): Category
    {
        return Category::query()
            ->whereKey($id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }
}
