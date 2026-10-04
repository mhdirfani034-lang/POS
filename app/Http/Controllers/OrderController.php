<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $orders = Order::query()
            ->with('customer')
            ->when($search !== '', fn ($query) => $query->where(fn ($query) => $query
                ->where('invoice_number', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%")))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('orders.index', compact('orders', 'search'));
    }

    public function show(Order $order): View
    {
        $order->load(['customer', 'items']);

        return view('orders.show', compact('order'));
    }
}
