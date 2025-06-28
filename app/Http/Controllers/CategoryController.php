<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        // Add breadcrumbs for the category list page
        $breadcrumbs = [['name' => 'Dashboard', 'url' => route('dashboard')], ['name' => 'Categories']];
        return view('categories.index', compact('categories', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name', 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',]);
        $imageName = 'default_category.png';
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/categories', $imageName);
        }
        Category::create(['name' => $request->name, 'image' => $imageName]);
        return redirect()->route('categories.index')->with('success', 'Category added successfully!');
    }

    public function destroy(Category $category)
    {
        if ($category->image && $category->image != 'default_category.png') {
            Storage::delete('public/categories/' . $category->image);
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
