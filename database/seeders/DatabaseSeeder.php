<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 2 Users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
        User::create([
            'name' => 'Normal User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // 3 Categories
        $electronics = Category::create(['name' => 'Electronics', 'description' => 'Electronic Devices']);
        $clothing = Category::create(['name' => 'Clothing', 'description' => 'Apparel and Fashion']);
        $home = Category::create(['name' => 'Home & Kitchen', 'description' => 'Home appliances and decor']);

        // 10 Products
        for ($i = 1; $i <= 4; $i++) {
            Product::create([
                'category_id' => $electronics->id,
                'name' => "Electronic Product $i",
                'description' => "This is a great electronic product.",
                'price' => rand(100, 1000) - 0.01,
                'stock' => rand(10, 50),
            ]);
        }
        for ($i = 1; $i <= 3; $i++) {
            Product::create([
                'category_id' => $clothing->id,
                'name' => "Clothing Item $i",
                'description' => "Comfortable and stylish.",
                'price' => rand(20, 100) - 0.01,
                'stock' => rand(50, 100),
            ]);
        }
        for ($i = 1; $i <= 3; $i++) {
            Product::create([
                'category_id' => $home->id,
                'name' => "Home Appliance $i",
                'description' => "Make your home better.",
                'price' => rand(50, 200) - 0.01,
                'stock' => rand(20, 40),
            ]);
        }
    }
}
