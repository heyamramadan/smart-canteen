<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Studentmodel;
use App\Models\BannedProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
class StudentController extends Controller
{  public function index()
  {
$students = Studentmodel::with('user')->oldest()->paginate(10);

      $parentUsers = User::where('role', 'ولي أمر')->get();

      return view('user.students', compact('students', 'parentUsers'));
  }
    //public function create()
   // {
     //     $parents = User::where('role', 'ولي أمر')->get();
    //return view('user.student', compact('parents'));
    //}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $parentUser = User::find($validated['user_id']);
        $fatherName = $parentUser->full_name ?? 'غير معروف';

        $studentData = [
            'full_name' => $validated['full_name'],
            'class' => $validated['class'],
            'user_id' => $validated['user_id'],
            'father_name' => $fatherName,
            'pin_code' => mt_rand(1000, 9999),
        ];

        if ($request->hasFile(key: 'image')) {
            $imagePath = $request->file('image')->store('students/images', 'public');
            $studentData['image_path'] = $imagePath;
        }

        Studentmodel::create($studentData);

        return redirect()->route('students.index')->with('success', 'تم إضافة الطالب بنجاح!');
    }


  public function search(Request $request)
{
    $searchQuery = $request->input('query');

    $students = Studentmodel::with(['user.wallet'])

        ->where(function ($query) use ($searchQuery) {
            $query->where('full_name', 'LIKE', "%$searchQuery%");

            if (is_numeric($searchQuery)) {
                $query->orWhere('student_id', $searchQuery);
            }

            $query->orWhereHas('user', function($q) use ($searchQuery) {
                $q->where('full_name', 'LIKE', "%$searchQuery%");
            });
        })
        ->get();

    return response()->json($students->map(function ($student) {
        return [
            'student_id'     => $student->student_id,
            'full_name'      => $student->full_name,
            'class'          => $student->class,
            'image_path'     => $student->image_path,
            'created_at'     => $student->created_at->format('Y-m-d'),
            'deleted_at'     => $student->deleted_at,
            'user_id'        => $student->user_id,
            'user_name'      => $student->user->full_name ?? 'غير معروف',
            'father_name'    => $student->user->full_name ?? 'غير معروف',
            'daily_limit'  => $student->daily_limit ?? 0,
        ];
    }));
}

//نحذفها
//public function edit(Studentmodel $student)
//{
 //   $parents = User::where('role', 'ولي أمر')->with('user')->get();
  //   $student->image_url = $student->image ? asset('storage/' . $student->image) : null;
  //  return view('user.edit_student', compact('student', 'parents'));
//}

public function update(Request $request, Studentmodel $student)
{
    // ✅ تحديث قواعد التحقق
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'class' => 'required|string|max:255',
        'user_id' => 'required|exists:users,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // ✅ جلب ولي الأمر مباشرة
    $parentUser = User::find($validated['user_id']);
    $fatherName = $parentUser->full_name ?? 'غير معروف';

    $studentData = [
        'full_name' => $validated['full_name'],
        'class' => $validated['class'],
        'user_id' => $validated['user_id'],
        'father_name' => $fatherName,
    ];

    if ($request->hasFile('image')) {
        if ($student->image_path && Storage::disk('public')->exists($student->image_path)) {
            Storage::disk('public')->delete($student->image_path);
        }
        $imagePath = $request->file('image')->store('students/images', 'public');
        $studentData['image_path'] = $imagePath;
    }

    $student->update($studentData);

    return redirect()->route('students.index')->with('success', 'تم تعديل بيانات الطالب بنجاح!');
}
public function getAllowedCategories($student_id)
{
    try {
     $student = Studentmodel::findOrFail($student_id);

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
                'daily_limit' => $student->daily_limit ?? 0,
'remaining_limit' => $this->calculateRemainingLimit($student),

                'pin_code' => strval($student->pin_code),
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ: ' . $e->getMessage()
        ], 500);
    }
}

private function calculateRemainingLimit($student)
{
    if (!$student) return 0;

    $todayPurchases = \App\Models\Order::where('student_id', $student->student_id)
        ->whereDate('created_at', today())
        ->sum('total_amount');

    $remaining = $student->daily_limit - $todayPurchases;
    return max($remaining, 0);
}


  public function destroy($id)
    {
        $student = Studentmodel::findOrFail($id);
        $student->delete();


        return redirect()->route('students.index')->with('success', 'تم أرشفة الطالب بنجاح!');
    }

public function showPinCode(Studentmodel $student)
{
    return response()->json([
        'pin_code' => $student->pin_code,
        'student_name' => $student->full_name
    ]);
}
}
