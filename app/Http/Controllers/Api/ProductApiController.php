<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductApiController extends Controller
{
    // جلب جميع التصنيفات مع المنتجات المرتبطة بها
    public function getCategoriesWithProducts()
    {
        $categories = Category::with(['products' => function($query) {
            $query->where('is_active', 1);
        }])->get();

        return response()->json([
            'message' => 'تم جلب التصنيفات مع المنتجات بنجاح',
            'categories' => $categories
        ]);
    }

    // جلب جميع المنتجات فقط (نشطة فقط)
    public function getAllProducts()
    {
        $products = Product::where('is_active', 1)
            ->with('category')
            ->get();

        return response()->json([
            'message' => 'تم جلب المنتجات بنجاح',
            'products' => $products
        ]);
    }

    // جلب تفاصيل منتج واحد
    public function getProduct($product_id)
    {
        $product = Product::where('product_id', $product_id)
            ->where('is_active', 1)
            ->with('category')
            ->first();

        if (!$product) {
            return response()->json(['message' => 'المنتج غير موجود'], 404);
        }

        return response()->json([
            'message' => 'تم جلب تفاصيل المنتج بنجاح',
            'product' => $product
        ]);
    }
}
