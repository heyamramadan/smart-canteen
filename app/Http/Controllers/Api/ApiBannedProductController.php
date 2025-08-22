<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannedProduct;

class ApiBannedProductController extends Controller
{
    // جلب جميع المنتجات الممنوعة لولي الأمر (مع خيار تصفية حسب طالب)
    public function index(Request $request)
    {
        $parent = $request->user();

        $query = BannedProduct::with(['product', 'student'])
            ->where('user_id', $parent->id);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $bannedProducts = $query->get();

        return response()->json([
            'banned_products' => $bannedProducts
        ]);
    }

    // إضافة منتج ممنوع لطفل معيّن
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'product_id' => 'required|exists:products,product_id',
        ]);

        $parent = $request->user();

        if ($parent->role !== 'ولي أمر') {
            return response()->json(['message' => 'المستخدم ليس ولي أمر.'], 403);
        }

        $exists = BannedProduct::where('user_id', $parent->id)
            ->where('student_id', $request->student_id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'المنتج ممنوع مسبقاً لهذا الطالب'], 409);
        }

        $bannedProduct = BannedProduct::create([
            'user_id'    => $parent->id,
            'student_id' => $request->student_id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'message'         => 'تم إضافة المنتج الممنوع بنجاح',
            'banned_product'  => $bannedProduct,
        ], 201);
    }

    // حذف منتج ممنوع بـ ban_id
    public function destroy(Request $request, $ban_id)
    {
        $parent = $request->user();

        $bannedProduct = BannedProduct::where('ban_id', $ban_id)
            ->where('user_id', $parent->id)
            ->first();

        if (!$bannedProduct) {
            return response()->json(['message' => 'لم يتم العثور على المنتج الممنوع'], 404);
        }

        $bannedProduct->delete();

        return response()->json(['message' => 'تم حذف المنتج الممنوع بنجاح']);
    }

    // حذف منتج ممنوع بالثنائي (student_id + product_id)
    public function destroyByProduct(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'product_id' => 'required|exists:products,product_id',
        ]);

        $parent = $request->user();

        $deleted = BannedProduct::where('user_id', $parent->id)
            ->where('student_id', $request->student_id)
            ->where('product_id', $request->product_id)
            ->delete();

        if ($deleted === 0) {
            return response()->json(['message' => 'لم يتم العثور على المنتج الممنوع'], 404);
        }

        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}
