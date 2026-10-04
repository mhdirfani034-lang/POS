<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_sale_uses_the_database_price_and_reduces_stock(): void
    {
        $product = Product::create([
            'sku' => 'KOP-001',
            'name' => 'Kopi Susu',
            'price' => 15000.25,
            'stock' => 5,
            'is_active' => true,
        ]);
        $customer = Customer::create(['name' => 'Dina']);

        $response = $this->post(route('pos.store'), [
            'customer_id' => $customer->id,
            'payment_method' => 'qris',
            'items' => [[
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => 1,
            ]],
        ]);

        $order = Order::with('items')->firstOrFail();

        $response->assertRedirect(route('orders.show', $order));
        $this->assertSame(3000050, $order->total);
        $this->assertSame(1500025, $order->items->first()->unit_price);
        $this->assertSame(2, $order->items->first()->quantity);
        $this->assertSame('Dina', $order->customer_name);
        $this->assertSame(3, $product->fresh()->stock);
    }

    public function test_insufficient_stock_rejects_the_whole_sale_without_changes(): void
    {
        $available = Product::create([
            'sku' => 'A-001',
            'name' => 'Produk tersedia',
            'price' => 1000,
            'stock' => 8,
            'is_active' => true,
        ]);
        $limited = Product::create([
            'sku' => 'B-001',
            'name' => 'Produk terbatas',
            'price' => 2000,
            'stock' => 1,
            'is_active' => true,
        ]);

        $response = $this->from(route('pos.create'))->post(route('pos.store'), [
            'payment_method' => 'tunai',
            'items' => [
                ['product_id' => $available->id, 'quantity' => 2],
                ['product_id' => $limited->id, 'quantity' => 2],
            ],
        ]);

        $response->assertRedirect(route('pos.create'))->assertSessionHasErrors('items');
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertSame(8, $available->fresh()->stock);
        $this->assertSame(1, $limited->fresh()->stock);
    }

    public function test_inactive_products_cannot_be_sold(): void
    {
        $product = Product::create([
            'sku' => 'NON-001',
            'name' => 'Produk nonaktif',
            'price' => 5000,
            'stock' => 10,
            'is_active' => false,
        ]);

        $this->from(route('pos.create'))->post(route('pos.store'), [
            'payment_method' => 'tunai',
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ])->assertRedirect(route('pos.create'))->assertSessionHasErrors('items');

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(10, $product->fresh()->stock);
    }

    public function test_cashier_and_saved_receipt_are_available(): void
    {
        $product = Product::create([
            'sku' => 'TEH-001',
            'name' => 'Teh Melati',
            'price' => 8000,
            'stock' => 4,
            'is_active' => true,
        ]);

        $this->get(route('pos.create'))->assertOk()->assertSee('Teh Melati');

        $this->post(route('pos.store'), [
            'payment_method' => 'tunai',
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ]);

        $order = Order::firstOrFail();
        $this->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee($order->invoice_number)
            ->assertSee('Teh Melati')
            ->assertSee('Rp 8.000,00');
    }
}
