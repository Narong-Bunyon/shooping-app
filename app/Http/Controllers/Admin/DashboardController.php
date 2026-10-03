<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $categoriesCount = Category::count();
        $productsCount = Product::count();
        $ordersCount = Order::count();
        $revenue = Order::where('status', 'completed')->sum('total_amount');

        return view('admin.dashboard', compact('categoriesCount', 'productsCount', 'ordersCount', 'revenue'));
    }
}
