<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_can_be_created_searched_updated_and_deleted(): void
    {
        $this->post(route('produk.store'), [
            'sku' => 'KOP-001',
            'name' => 'Kopi Susu',
            'category' => 'Minuman',
            'price' => 18000,
            'stock' => 12,
            'is_active' => '1',
        ])->assertRedirect(route('produk.index'));

        $product = Product::firstOrFail();
        $this->get(route('produk.index', ['q' => 'KOP-001']))->assertOk()->assertSee('Kopi Susu');

        $this->put(route('produk.update', $product), [
            'sku' => 'KOP-001',
            'name' => 'Kopi Susu Baru',
            'category' => 'Minuman',
            'price' => 20000,
            'stock' => 10,
            'is_active' => '1',
        ])->assertRedirect(route('produk.index'));

        $this->assertSame('20000.00', $product->fresh()->price);
        $this->delete(route('produk.destroy', $product))->assertRedirect(route('produk.index'));
        $this->assertDatabaseCount('products', 0);
    }

    public function test_customer_search_and_crud_work(): void
    {
        $this->post(route('pelanggan.store'), [
            'name' => 'Sari Wulandari',
            'email' => 'sari@example.com',
            'phone' => '081200000001',
            'address' => 'Surabaya',
        ])->assertRedirect(route('pelanggan.index'));

        $customer = Customer::firstOrFail();
        $this->get(route('pelanggan.index', ['q' => '081200000001']))->assertOk()->assertSee('Sari Wulandari');

        $this->put(route('pelanggan.update', $customer), [
            'name' => 'Sari Wulandari',
            'email' => 'sari@example.com',
            'phone' => '081200000002',
            'address' => 'Malang',
            'notes' => 'Pelanggan tetap',
        ])->assertRedirect(route('pelanggan.index'));

        $this->assertSame('081200000002', $customer->fresh()->phone);
        $this->delete(route('pelanggan.destroy', $customer))->assertRedirect(route('pelanggan.index'));
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_invalid_product_price_and_duplicate_customer_email_are_rejected(): void
    {
        $this->post(route('produk.store'), [
            'sku' => 'BAD-001',
            'name' => 'Harga tidak valid',
            'price' => -1,
            'stock' => 2,
        ])->assertSessionHasErrors('price');

        Customer::create(['name' => 'Pelanggan awal', 'email' => 'duplikat@example.com']);

        $this->post(route('pelanggan.store'), [
            'name' => 'Pelanggan lain',
            'email' => 'duplikat@example.com',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseCount('customers', 1);
    }

    public function test_validation_messages_use_indonesian_labels(): void
    {
        $this->post(route('produk.store'), [
            'sku' => 'KOP-001',
            'price' => 10000,
            'stock' => 1,
        ])->assertSessionHasErrors('name');

        $this->assertSame(
            'nama wajib diisi.',
            __('validation.required', ['attribute' => __('validation.attributes.name')]),
        );
    }
}
