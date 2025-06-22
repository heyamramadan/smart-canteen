<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use App\Models\Studentmodel;
use App\Models\BannedProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
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
           'parent_id' => 'required|exists:parents,parent_id',
            'image_path'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

          //  جلب ولي الأمر المرتبط مع معلومات المستخدم
        $parent = ParentModel::with('user')->find($validated['parent_id']);

        //  اسم الأب من اسم المستخدم المرتبط جاستخراجديد
        $fatherName = $parent->user->full_name ?? 'غير معروف';

            // معالجة تحميل الصورة
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('students/images', 'public');
        $validated['image_path'] = $imagePath;
    }
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
    public function index()
    {
        $students = Studentmodel::withTrashed()->get();
        $parents = ParentModel::with('user')->get();
        return view('user.students', compact('students', 'parents'));
    }
public function search(Request $request)
{
    $searchQuery = $request->input('query');

    // الاستعلام مع العلاقات المطلوبة والطلاب المؤرشفين
    $query = Studentmodel::with(['parent.user'])->withTrashed();

    if (!empty($searchQuery)) {
        $isIdSearch = is_numeric($searchQuery);

        $query->where(function($q) use ($searchQuery, $isIdSearch) {
            $q->where('full_name', 'LIKE', '%' . $searchQuery . '%')
              ->orWhere('father_name', 'LIKE', '%' . $searchQuery . '%')
              ->orWhere('class', 'LIKE', '%' . $searchQuery . '%')
              ->orWhereHas('parent.user', function($q) use ($searchQuery) {
                  $q->where('full_name', 'LIKE', '%' . $searchQuery . '%');
              });

            if ($isIdSearch) {
                $q->orWhere('student_id', $searchQuery);
            }
        });
    }

    $students = $query->get();

    return response()->json($students);
}

// عرض صفحة تعديل بيانات الطالب
// عرض صفحة تعديل بيانات الطالب
public function edit(Studentmodel $student)
{
    $parents = ParentModel::with('user')->get();
     $student->image_url = $student->image ? asset('storage/' . $student->image) : null;
    return view('user.edit_student', compact('student', 'parents'));
}

// تحديث بيانات الطالب
public function update(Request $request, Studentmodel $student)
{
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'class' => 'required|string|max:255',
        'birth_date' => 'nullable|date',
        'parent_id' => 'required|exists:parents,parent_id',
          'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'

    ]);

    $parent = ParentModel::with('user')->find($validated['parent_id']);
    $fatherName = $parent->user->full_name ?? 'غير معروف';
      // معالجة تحميل الصورة
  if ($request->hasFile('image')) {
    if ($student->image_path && Storage::disk('public')->exists($student->image_path)) {
        Storage::disk('public')->delete($student->image_path);
    }
    $imagePath = $request->file('image')->store('students/images', 'public');
    $validated['image_path'] = $imagePath; // استخدم image_path بدل image
}

    $student->update(array_merge($validated, [
        'father_name' => $fatherName
    ]));

    return redirect()->route('students.index')->with('success', 'تم تعديل بيانات الطالب بنجاح!');
}
public function getAllowedCategories($student_id)
{
    try {
        $student = Studentmodel::with(['parent.wallet'])->findOrFail($student_id);

        $bannedProductIds = BannedProduct::where('student_id', $student_id)
            ->pluck('product_id')
            ->toArray();

        $allowedProducts = Product::where('is_active', 1)
            ->whereNotIn('product_id', $bannedProductIds)
            ->with('category')
            ->get();

        $categories = [];
        $productsData = [];

        foreach ($allowedProducts as $product) {
            if (!$product->category) continue;

            if (!isset($categories[$product->category->category_id])) {
                $categories[$product->category->category_id] = [
                    'category_id' => $product->category->category_id,
                    'name' => $product->category->name,
                ];
            }

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
            'categories' => array_values($categories),
            'products' => $productsData,
            'student' => [
                'student_id' => $student->student_id,
                'full_name' => $student->full_name,
                'father_name' => $student->father_name,
                'class' => $student->class,
                'daily_limit' => $student->parent->wallet->daily_limit ?? 0,
                'remaining_limit' => $this->calculateRemainingLimit($student->parent->wallet)
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ: ' . $e->getMessage()
        ], 500);
    }
}

private function calculateRemainingLimit($wallet)
{
    if (!$wallet) return 0;

    // حساب إجمالي المشتريات اليومية لهذا الطالب
    $todayPurchases = \App\Models\Order::whereHas('student', function($q) use ($wallet) {
            $q->where('parent_id', $wallet->parent_id);
        })
        ->whereDate('created_at', today())
        ->sum('total_amount');

    $remaining = $wallet->daily_limit - $todayPurchases;
    return max($remaining, 0); // التأكد من عدم الرجوع بقيمة سالبة
}
  public function destroy($id)
    {
        $student = Studentmodel::findOrFail($id);
        $student->delete();

        // يمكنك هنا إضافة أي عمليات إضافية تحتاجها عند أرشفة الطالب
        // مثل أرشفة المنتجات المحظورة أو الطلبات المرتبطة به

        return redirect()->route('students.index')->with('success', 'تم أرشفة الطالب بنجاح!');
    }

    public function restore($id)
    {
        $student = Studentmodel::onlyTrashed()->findOrFail($id);
        $student->restore();

        // يمكنك هنا إضافة أي عمليات إضافية تحتاجها عند استعادة الطالب

        return redirect()->route('students.index')->with('success', 'تم استعادة الطالب بنجاح.');
    }

}
