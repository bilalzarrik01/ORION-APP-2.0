<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Category::query();
        if (! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $categories = $query
            ->withCount('links')
            ->with('user')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('create', Category::class);

        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        Category::create([
            'name' => $validated['name'],
            'user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('categories.index')
            ->with('status', 'Category created.');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('status', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('status', 'Category deleted.');
    }
}
