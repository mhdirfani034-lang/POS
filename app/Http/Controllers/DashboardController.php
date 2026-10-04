<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'productCount' => Product::count(),
            'customerCount' => Customer::count(),
            'todayOrders' => Order::whereDate('created_at', today())->count(),
            'todayRevenue' => Order::whereDate('created_at', today())->sum('total'),
            'lowStockProducts' => Product::where('is_active', true)->where('stock', '<=', 5)->orderBy('stock')->take(5)->get(),
            'recentOrders' => Order::with('customer')->latest()->take(6)->get(),
        ]);
    }
}
