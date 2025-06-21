<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StudentModel;  // حرف A في App كبير واسم الكلاس بنفس الطريقة كما في الموديل
use Illuminate\Http\Request;

class CardController extends Controller
{
   public function index()
    {
        $students = StudentModel::all();
        return view('cards', compact('students'));
    }

    public function fetch(Request $request)
    {
        $students = StudentModel::all();
        return response()->json($students);
    }
}
