<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_categories,create_categories,edit_categories,delete_categories']);
    }

    public function index(): View
    {
        $categories = Category::with('parent', 'products')
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $categories = Category::active()
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'slug' => ['required', 'string', 'max:255', 'unique:categories,slug'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $filename = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('categories', $filename, 'public');
            $validated['image'] = $filename;
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        $categories = Category::active()
            ->whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'slug' => ['required', 'string', 'max:255', 'unique:categories,slug,' . $category->id],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete('categories/' . $category->image);
            }
            $filename = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('categories', $filename, 'public');
            $validated['image'] = $filename;
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with subcategories.');
        }

        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with associated products.');
        }

        if ($category->image) {
            Storage::disk('public')->delete('categories/' . $category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function toggleStatus(Category $category): RedirectResponse
    {
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.categories.index')
            ->with('success', "Category {$status} successfully.");
    }
}
