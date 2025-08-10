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
    // عرض قائمة التصنيفات مع بحث وتعداد المنتجات
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

    
    // حفظ التصنيف الجديد
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

    // إظهار نموذج تعديل تصنيف موجود
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('categories_edit', compact('category'));
    }

    // تحديث بيانات التصنيف
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

    // حذف تصنيف
    public function destroy($id)
    {
         $category = Category::withCount('products')->findOrFail($id);

    // تحقق إذا كان يحتوي على منتجات
    if ($category->products_count > 0) {
        return redirect()->route('categories.index')
            ->with('success', '❌ لا يمكن حذف هذا التصنيف لأنه مرتبط بمنتجات حالية.');
    }

    $category->delete();

    return redirect()->route('categories.index')
        ->with('success', '✅ تم حذف التصنيف بنجاح.');

    }



public function getAllowedCategories($id)
{
    // 1. التحقق من وجود الطالب
    $student = Studentmodel::findOrFail($id);

    // 2. قائمة المنتجات الممنوعة (بيانات اختبارية مؤقتة)
    $bannedProductIds = [1, 3, 5]; // يمكنك تغيير هذه الأرقام حسب احتياجك

    // 3. جلب المنتجات غير الممنوعة
    $allowedProducts = Product::with('category')
        ->whereNotIn('product_id', $bannedProductIds)
        ->where('is_active', true)
        ->get();

    // 4. تجميع البيانات للإرجاع
    $categories = $allowedProducts->groupBy('category.id')->map(function($products, $categoryId) {
        return [
            'id' => $categoryId,
            'name' => $products->first()->category->name ?? 'غير محدد'
        ];
    })->values();

    return response()->json([
        'categories' => $categories,
        'products' => $allowedProducts->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'category_name' => $product->category->name ?? 'غير محدد'
            ];
        })
    ]);
}
}
