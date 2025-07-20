<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    // عرض المنتجات مع بحث بسيط
 public function index(Request $request)
{
    $search = $request->input('search');

    $products = Product::when($search, function($query, $search) {
        return $query->where('name', 'like', "%$search%");
    })->get();

    $categories = Category::all(); // جلب الأصناف

    return view('products', compact('products', 'search', 'categories'));
}
    // حفظ منتج جديد
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
        ]);

        return redirect()->route('products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }

    // عرض بيانات المنتج للتعديل (يمكن جعلها جزء من المودال)
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products_edit', compact('product'));
    }

    // تحديث بيانات المنتج
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
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'تم تعديل المنتج بنجاح');
    }

    // حذف المنتج
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح');
    }
}
