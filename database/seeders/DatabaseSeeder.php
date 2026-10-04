<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Product::upsert([
            ['sku' => 'KOP-001', 'name' => 'Kopi Susu Gula Aren', 'category' => 'Minuman', 'price' => 18000, 'stock' => 24, 'description' => 'Kopi susu dengan gula aren.', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['sku' => 'TEH-001', 'name' => 'Es Teh Melati', 'category' => 'Minuman', 'price' => 8000, 'stock' => 32, 'description' => 'Teh melati segar.', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['sku' => 'ROT-001', 'name' => 'Roti Bakar Cokelat', 'category' => 'Makanan', 'price' => 15000, 'stock' => 3, 'description' => 'Roti bakar isi cokelat.', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['sku' => 'SNK-001', 'name' => 'Keripik Singkong', 'category' => 'Camilan', 'price' => 12000, 'stock' => 18, 'description' => 'Camilan singkong renyah.', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ], ['sku'], ['name', 'category', 'price', 'stock', 'description', 'is_active', 'updated_at']);

        Customer::upsert([
            ['name' => 'Dina Pratama', 'email' => 'dina@example.com', 'phone' => '081234567890', 'address' => 'Bandung', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rizky Saputra', 'email' => 'rizky@example.com', 'phone' => '081298765432', 'address' => 'Jakarta', 'created_at' => now(), 'updated_at' => now()],
        ], ['email'], ['name', 'phone', 'address', 'updated_at']);
    }
}
