<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use App\Models\Studentmodel;
use App\Models\BannedProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
class StudentController extends Controller
{
    // دالة عرض صفحة إضافة طالب جديد
    public function create()
    {
          $parents = \App\Models\ParentModel::with('user')->get();
    return view('user.student', compact('parents'));
    }

    // دالة تخزين بيانات الطالب الجديدة
    public function store(Request $request)
    {
        // تحقق من صحة البيانات (Validation)
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'class' => 'required|string|max:255',
           'parent_id' => 'required|exists:parents,parent_id'
           // 'birth_date' => 'nullable|date',
        ]);

          //  جلب ولي الأمر المرتبط مع معلومات المستخدم
        $parent = ParentModel::with('user')->find($validated['parent_id']);

        //  اسم الأب من اسم المستخدم المرتبط جاستخراجديد
        $fatherName = $parent->user->full_name ?? 'غير معروف';

    // دمج البيانات مع اسم الأب
        $studentData = array_merge($validated, [
            'father_name' => $fatherName,
        ]);
           //  إنشاء الطالب في قاعدة البيانات جديدة
        Studentmodel::create($studentData);


        // إعادة التوجيه مع رسالة نجاح إلى قائمة الطلاب
        return redirect()->route('students.index')->with('success', 'تم إضافة الطالب بنجاح!');
    }

    // دالة عرض قائمة الطلاب
    public function index(){

   $students = Studentmodel::all();
    $parents = ParentModel::with('user')->get(); // تأكد من استدعاء موديل ولي الأمر و علاقته

    return view('user.students', compact('students', 'parents'));

}
public function search(Request $request)
{
    $query = $request->input('query');

    $students = Studentmodel::with('parent.user')
        ->when($query, function($q) use ($query) {
            $q->where('full_name', 'LIKE', '%' . $query . '%')
              ->orWhere('father_name', 'LIKE', '%' . $query . '%')
              ->orWhere('class', 'LIKE', '%' . $query . '%')
              ->orWhereHas('parent.user', function($q) use ($query) {
                  $q->where('full_name', 'LIKE', '%' . $query . '%');
              });
        })
        ->get();

    return response()->json($students);
}
// عرض صفحة تعديل بيانات الطالب
// عرض صفحة تعديل بيانات الطالب
public function edit(Studentmodel $student)
{
    $parents = ParentModel::with('user')->get();
    return view('user.edit_student', compact('student', 'parents'));
}

// تحديث بيانات الطالب
public function update(Request $request, Studentmodel $student)
{
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'class' => 'required|string|max:255',
        'birth_date' => 'nullable|date',
        'parent_id' => 'required|exists:parents,parent_id'
    ]);

    $parent = ParentModel::with('user')->find($validated['parent_id']);
    $fatherName = $parent->user->full_name ?? 'غير معروف';

    $student->update(array_merge($validated, [
        'father_name' => $fatherName
    ]));

    return redirect()->route('students.index')->with('success', 'تم تعديل بيانات الطالب بنجاح!');
}
public function getAllowedCategories($student_id)
{
    try {
        // تحقق أن الطالب موجود
        $student = Studentmodel::findOrFail($student_id);

        // جلب معرفات المنتجات المحظورة لهذا الطالب
        $bannedProductIds = BannedProduct::where('student_id', $student_id)
            ->pluck('product_id')
            ->toArray();

        // جلب المنتجات المفعلة وليست محظورة مع التصنيفات
        $allowedProducts = Product::where('is_active', 1)
            ->whereNotIn('product_id', $bannedProductIds)
            ->with('category')
            ->get();

        // تحضير البيانات للإرجاع كـ JSON
        $categories = [];
        $productsData = [];

        foreach ($allowedProducts as $product) {
            if (!$product->category) continue;

            // إضافة التصنيف إذا لم يكن موجوداً
            if (!isset($categories[$product->category->category_id])) {
                $categories[$product->category->category_id] = [
                    'category_id' => $product->category->category_id,
                    'name' => $product->category->name,
                ];
            }

            // إضافة بيانات المنتج
            $productsData[] = [
                'id' => $product->product_id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'category_id' => $product->category->category_id,
                'category_name' => $product->category->name,
            ];
        }

        return response()->json([
            'success' => true,
            'categories' => array_values($categories), // تحويل إلى مصفوفة عددية
            'products' => $productsData,
            'student' => [
                'student_id' => $student->student_id,
                'full_name' => $student->full_name,
                'father_name' => $student->father_name,
                'class' => $student->class,
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ: ' . $e->getMessage()
        ], 500);
    }
}

}
