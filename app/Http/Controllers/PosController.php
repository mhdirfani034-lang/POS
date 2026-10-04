<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PosController extends Controller
{
    public function create(): View
    {
        return view('pos.create', [
            'products' => Product::where('is_active', true)->where('stock', '>', 0)->orderBy('name')->get(),
            'customers' => Customer::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'payment_method' => ['required', 'in:tunai,kartu,transfer,qris'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $quantities = collect($data['items'])
            ->mapWithKeys(fn (array $item) => [(int) $item['product_id'] => (int) $item['quantity']]);
        $customer = isset($data['customer_id']) ? Customer::findOrFail($data['customer_id']) : null;

        $order = DB::transaction(function () use ($quantities, $customer, $data): Order {
            $products = Product::whereIn('id', $quantities->keys())
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get($productId);

                if (! $product || ! $product->is_active) {
                    throw ValidationException::withMessages([
                        'items' => 'Salah satu produk tidak tersedia. Muat ulang halaman kasir dan coba lagi.',
                    ]);
                }

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak mencukupi. Tersedia {$product->stock} unit.",
                    ]);
                }
            }

            $subtotal = 0;
            $lines = [];

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get($productId);
                $unitPrice = $product->priceInCents();
                $lineTotal = $unitPrice * $quantity;
                $subtotal += $lineTotal;
                $lines[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            $order = Order::create([
                'invoice_number' => 'POS-'.Str::ulid(),
                'customer_id' => $customer?->id,
                'customer_name' => $customer?->name,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'payment_method' => $data['payment_method'],
            ]);

            foreach ($lines as $line) {
                $product = $line['product'];
                $quantity = $line['quantity'];

                $updated = Product::whereKey($product->id)
                    ->where('is_active', true)
                    ->where('stock', '>=', $quantity)
                    ->decrement('stock', $quantity);

                if ($updated !== 1) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} berubah. Muat ulang halaman kasir dan coba lagi.",
                    ]);
                }

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $line['unit_price'],
                    'line_total' => $line['line_total'],
                ]);
            }

            return $order;
        }, attempts: 3);

        return redirect()->route('orders.show', $order)->with('success', 'Transaksi berhasil disimpan.');
    }
}
