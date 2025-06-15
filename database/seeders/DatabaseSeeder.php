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

        DB::table('products')->insert([
            [
                'product_code' => '#ZY9201',
                'name' => 'Vas Keramik Bunga',
                'category_id' => 1,
                'stock' => 100,
                'price' => 1200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_code' => '#EL1001',
                'name' => 'Kipas Angin Portable',
                'category_id' => 2,
                'stock' => 50,
                'price' => 350000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
