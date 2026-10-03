<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('status', true)->paginate(12);
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        if(!$product->status) abort(404);
        return view('products.show', compact('product'));
    }

    public function byCategory(Category $category)
    {
        $products = $category->products()->where('status', true)->paginate(12);
        return view('products.index', compact('products'));
    }
}
