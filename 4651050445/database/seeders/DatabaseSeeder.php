<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Laptop Dell',
            'description' => 'Laptop demo cho dịch vụ quản lý sản phẩm.',
            'price' => 18500000,
            'quantity' => 10,
        ]);

        Product::create([
            'name' => 'Mouse Logitech',
            'description' => 'Chuột không dây demo.',
            'price' => 450000,
            'quantity' => 25,
        ]);
    }
}
