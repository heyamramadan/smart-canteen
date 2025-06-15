<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('categories', compact('categories', 'search'));
    }

    // إظهار نموذج إنشاء تصنيف جديد
    public function create()
    {
        return view('categories_create');
    }

    // حفظ التصنيف الجديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
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
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'تم تعديل التصنيف بنجاح.');
    }

    // حذف تصنيف
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'تم حذف التصنيف بنجاح.');
    }
    public function getCategoriesForStudent($student_id)
{
    $student = \App\Models\StudentModel::findOrFail($student_id);

    $categories = Category::with(['products' => function ($query) use ($student) {
        // فلترة المنتجات التي **ليست ممنوعة** لهذا الطالب
        $query->whereDoesntHave('bannedProducts', function ($q) use ($student) {
            $q->where('student_id', $student->student_id);
        });
    }])->get();

    return response()->json($categories);
}

}
