<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $selectedCategory = $request->input('category_id');
        $query = Product::with('category')->orderBy('name');
        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory);
        }
        $products = $query->paginate($limit == 0 ? 9999 : $limit)->withQueryString();
        $categories = Category::orderBy('name')->get();

        // Add breadcrumbs for the product list page
        $breadcrumbs = [['name' => 'Dashboard', 'url' => route('dashboard')], ['name' => 'Products']];

        return view('products.index', compact('products', 'categories', 'selectedCategory', 'limit', 'breadcrumbs'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        // Add breadcrumbs for the create product page
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Products', 'url' => route('products.index')],
            ['name' => 'Add New Product']
        ];
        return view('products.create', compact('categories', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();
            $imageName = 'default.png';
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('public/products', $imageName);
            }
            $product = Product::create($request->only(['name', 'category_id', 'price', 'quantity']) + ['image' => $imageName]);
            $randomNumber = mt_rand(100000000, 999999999);
            $sku = 'MPOS' . $randomNumber;
            while (Product::where('sku', $sku)->exists()) {
                $randomNumber = mt_rand(100000000, 999999999);
                $sku = 'MPOS' . $randomNumber;
            }
            $product->sku = $sku;
            $product->save();
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product added successfully with SKU: ' . $sku);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add product: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        // Add breadcrumbs for the edit product page
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Products', 'url' => route('products.index')],
            ['name' => 'Edit Product']
        ];
        return view('products.edit', compact('product', 'categories', 'breadcrumbs'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $input = $request->except(['_token', '_method']);
        if ($request->hasFile('image')) {
            if ($product->image && $product->image != 'default.png') {
                Storage::delete('public/products/' . $product->image);
            }
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/products', $imageName);
            $input['image'] = $imageName;
        }
        $product->update($input);
        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function toggleStatus(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();
        $action = $product->is_active ? 'Enabled' : 'Disabled';
        return redirect()->back()->with('success', "Product status changed to $action.");
    }
}
