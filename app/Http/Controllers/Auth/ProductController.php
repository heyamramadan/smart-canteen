<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

use Illuminate\Http\Request;

class ProductController extends Controller
{
   
 public function index(Request $request)
{
    $search = $request->input('search');

    $products = Product::when($search, function($query, $search) {
        return $query->where('name', 'like', "%$search%");
    })->get();

    $categories = Category::all();

    return view('products', compact('products', 'search', 'categories'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
             'is_active' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
            'expiry_date' => 'required|date|after_or_equal:today',

        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? '',
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'is_active' => $validated['is_active'],
            'image' => $validated['image'] ?? null,
             'expiry_date' => $validated['expiry_date'],
        ]);

        return redirect()->route('products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }




    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
              'category_id' => 'required|exists:categories,category_id',
            'name' => 'required|string|max:255',
           'description' => 'required|string',
            'price' => 'required|numeric',
             'quantity' => 'required|integer',
          'is_active' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
                 'expiry_date' => 'required|date|after_or_equal:today',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }
$validated['is_active'] = $validated['quantity'] > 0 ? 1 : 0;

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'تم تعديل المنتج بنجاح');
    }


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح');
    }
}
