<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use  App\Models\BannedProduct;
use  App\Models\studentmodel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $categories = Category::withCount('products')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('categories', compact('categories', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'تم إضافة التصنيف بنجاح.');
    }


    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->category_id . ',category_id',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'تم تعديل التصنيف بنجاح.');
    }


    public function destroy($id)
    {
         $category = Category::withCount('products')->findOrFail($id);

    if ($category->products_count > 0) {
        return redirect()->route('categories.index')
            ->with('success', '❌ لا يمكن حذف هذا التصنيف لأنه مرتبط بمنتجات حالية.');
    }

    $category->delete();

    return redirect()->route('categories.index')
        ->with('success', '✅ تم حذف التصنيف بنجاح.');

    }
}
