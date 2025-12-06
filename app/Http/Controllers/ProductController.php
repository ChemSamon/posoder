<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Show POS page
    public function pos(Request $request)
{
    $categories = Category::all(); // ✅ This line ensures categories are available

    $selectedCategory = $request->get('category_id');

    $products = Product::when($selectedCategory, function ($query, $selectedCategory) {
        return $query->where('category_id', $selectedCategory);
    })->get();

    return view('pos.index', compact('products', 'categories', 'selectedCategory'));
}

    // Display list of products with category filter
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Product::query();

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search') && $request->search != '') {
            $txt = $request->search;
            $query->where(function ($q) use ($txt) {
                $q->where('name', 'like', "%{$txt}%")
                  ->orWhere('price', 'like', "%{$txt}%");
            });
        }

        $products = $query->orderBy('id', 'DESC')->paginate(8);

        return view('product.index', compact('products', 'categories'));
    }

    // Show form to create a new product
    public function create()
    {
        $categories = Category::all();
        return view('product.create', compact('categories'));
    }

    // Store a newly created product
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'price', 'description', 'category_id']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('image', 'Custom');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    // Show the form to edit a product
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('product.edit', compact('product', 'categories'));
    }

    // Update an existing product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'price', 'description', 'category_id']);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('Custom')->exists($product->image)) {
                Storage::disk('Custom')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('image', 'Custom');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    // Delete a product
    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('Custom')->exists($product->image)) {
            Storage::disk('Custom')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
