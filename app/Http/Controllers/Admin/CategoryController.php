<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        // Since we are skipping admin views for now to save time, we will just return a placeholder or JSON
        return response()->json($categories);
    }

    // other methods...
}
