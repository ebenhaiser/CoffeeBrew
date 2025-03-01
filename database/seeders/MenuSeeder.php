<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::insert([
            // Coffee
            ['name' => 'Espresso', 'description' => 'Strong black coffee with a rich taste.', 'category_id' => 1, 'price' => 15000, 'stock' => 50],
            ['name' => 'Americano', 'description' => 'Espresso with added hot water.', 'category_id' => 1, 'price' => 18000, 'stock' => 40],
            ['name' => 'Cappuccino', 'description' => 'Espresso with steamed milk and foam.', 'category_id' => 1, 'price' => 25000, 'stock' => 30],
            ['name' => 'Latte', 'description' => 'Espresso with more steamed milk.', 'category_id' => 1, 'price' => 27000, 'stock' => 30],

            // Non-Coffee
            ['name' => 'Matcha Latte', 'description' => 'Green tea matcha with milk.', 'category_id' => 2, 'price' => 30000, 'stock' => 20],
            ['name' => 'Chocolate Milk', 'description' => 'Sweet and delicious chocolate milk.', 'category_id' => 2, 'price' => 25000, 'stock' => 25],
            ['name' => 'Fresh Orange Juice', 'description' => 'Fresh natural orange juice.', 'category_id' => 2, 'price' => 22000, 'stock' => 15],

            // Snack
            ['name' => 'French Fries', 'description' => 'Crispy fried potatoes.', 'category_id' => 3, 'price' => 20000, 'stock' => 40],
            ['name' => 'Onion Rings', 'description' => 'Crispy battered onion rings.', 'category_id' => 3, 'price' => 22000, 'stock' => 35],
            ['name' => 'Chicken Nuggets', 'description' => 'Crispy chicken nuggets.', 'category_id' => 3, 'price' => 25000, 'stock' => 25],

            // Plater
            ['name' => 'Mix Platter', 'description' => 'A mix of snacks like fries, onion rings, and nuggets.', 'category_id' => 4, 'price' => 45000, 'stock' => 15],

            // Main Course
            ['name' => 'Chicken Steak', 'description' => 'Chicken steak with black pepper sauce.', 'category_id' => 5, 'price' => 55000, 'stock' => 20],
            ['name' => 'Beef Burger', 'description' => 'Beef burger with cheese and fresh vegetables.', 'category_id' => 5, 'price' => 50000, 'stock' => 25],
            ['name' => 'Spaghetti Bolognese', 'description' => 'Pasta with beef sauce.', 'category_id' => 5, 'price' => 48000, 'stock' => 30],

            // Dessert
            ['name' => 'Cheesecake', 'description' => 'Soft cheesecake with strawberry topping.', 'category_id' => 6, 'price' => 35000, 'stock' => 20],
            ['name' => 'Chocolate Brownie', 'description' => 'Soft and sweet chocolate brownie.', 'category_id' => 6, 'price' => 28000, 'stock' => 25],
            ['name' => 'Ice Cream Sundae', 'description' => 'Vanilla ice cream with chocolate sauce.', 'category_id' => 6, 'price' => 27000, 'stock' => 30],
        ]);
    }
}
