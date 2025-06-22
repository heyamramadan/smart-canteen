<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannedProduct;
use App\Models\ParentModel;

class ApiBannedProductController extends Controller
{
  // جلب جميع المنتجات الممنوعة المرتبطة بولي الأمر (أو لطفل معين)
    public function index(Request $request)
    {
        $parent = $request->user()->parent;

        $bannedProducts = BannedProduct::with('product', 'student')
            ->where('parent_id', $parent->parent_id)
            ->get();

        return response()->json(['banned_products' => $bannedProducts]);
    }

    // إضافة منتج ممنوع لطفل معين
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'product_id' => 'required|exists:products,product_id',
        ]);

        $parent = $request->user()->parent;

        // تحقق إذا المنتج ممنوع بالفعل
        $exists = BannedProduct::where('parent_id', $parent->parent_id)
            ->where('student_id', $request->student_id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'المنتج ممنوع مسبقاً لهذا الطالب'], 409);
        }

        $bannedProduct = BannedProduct::create([
            'parent_id' => $parent->parent_id,
            'student_id' => $request->student_id,
            'product_id' => $request->product_id,
            'created_at' => now(),
        ]);

        return response()->json([
            'message' => 'تم إضافة المنتج الممنوع بنجاح',
            'banned_product' => $bannedProduct,
        ]);
    }

    // حذف منتج ممنوع
    public function destroy(Request $request, $ban_id)
    {
        $parent = $request->user()->parent;

        $bannedProduct = BannedProduct::where('ban_id', $ban_id)
            ->where('parent_id', $parent->parent_id)
            ->first();

        if (!$bannedProduct) {
            return response()->json(['message' => 'لم يتم العثور على المنتج الممنوع'], 404);
        }

        $bannedProduct->delete();

        return response()->json(['message' => 'تم حذف المنتج الممنوع بنجاح']);
    }}
