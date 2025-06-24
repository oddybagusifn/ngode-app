<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $categories = [
            'Pangan',
            'Fashion',
            'Agribisnis',
            'Kerajinan Tangan',
            'Kecantikan',
            'Kesehatan dan Kebugaran',
            'Tekstil',
            'Kreatif'
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Product::insert([
            [
                'product_code' => '#KR001',
                'name' => 'Vas Bunga Etnik Jawa',
                'category_id' => 1,
                'stock' => 20,
                'price' => 350000,
                'image' => 'tes.jpg',
                'description' => 'Vas keramik etnik dengan ukiran khas Jawa. Cocok untuk ruang tamu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_code' => '#KR002',
                'name' => 'Cangkir Kopi Antik',
                'category_id' => 1,
                'stock' => 40,
                'price' => 125000,
                'image' => 'tes.jpg',
                'description' => 'Cangkir keramik klasik, cocok untuk pecinta kopi sejati.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_code' => '#EL001',
                'name' => 'Kipas Meja Mini USB',
                'category_id' => 2,
                'stock' => 60,
                'price' => 150000,
                'image' => 'tes.jpg',
                'description' => 'Kipas angin mini dengan kabel USB. Ringkas dan praktis untuk meja kerja.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_code' => '#EL002',
                'name' => 'Humidifier Lampu Aroma',
                'category_id' => 2,
                'stock' => 35,
                'price' => 210000,
                'image' => 'tes.jpg',
                'description' => 'Humidifier multifungsi dengan lampu tidur dan diffuser aroma terapi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_code' => '#EL003',
                'name' => 'Lampu Tidur LED Kayu',
                'category_id' => 2,
                'stock' => 25,
                'price' => 185000,
                'image' => 'tes.jpg',
                'description' => 'Lampu tidur unik dengan desain kayu minimalis dan cahaya hangat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
