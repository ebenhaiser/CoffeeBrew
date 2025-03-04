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
            ['name' => 'Espresso', 'description' => 'Strong black coffee with a rich taste.', 'category_id' => 1, 'price' => 15000, 'stock' => 50, 'image' => 'img/menu/espresso.jpg'],
            ['name' => 'Americano', 'description' => 'Espresso with added hot water.', 'category_id' => 1, 'price' => 18000, 'stock' => 40, 'image' => 'img/menu/americano.jpg'],
            ['name' => 'Cappuccino', 'description' => 'Espresso with steamed milk and foam.', 'category_id' => 1, 'price' => 25000, 'stock' => 30, 'image' => 'img/menu/cappuccino.jpg'],
            ['name' => 'Latte', 'description' => 'Espresso with more steamed milk.', 'category_id' => 1, 'price' => 27000, 'stock' => 30, 'image' => 'img/menu/latte.jpg'],

            // Non-Coffee
            ['name' => 'Matcha Latte', 'description' => 'Green tea matcha with milk.', 'category_id' => 2, 'price' => 30000, 'stock' => 20, 'image' => 'img/menu/matcha-latte.jpg'],
            ['name' => 'Chocolate Milk', 'description' => 'Sweet and delicious chocolate milk.', 'category_id' => 2, 'price' => 25000, 'stock' => 25, 'image' => 'img/menu/chocolate-milk.jpg'],
            ['name' => 'Fresh Orange Juice', 'description' => 'Fresh natural orange juice.', 'category_id' => 2, 'price' => 22000, 'stock' => 15, 'image' => 'img/menu/fresh-orange-juice.jpg'],

            // Snack
            ['name' => 'French Fries', 'description' => 'Crispy fried potatoes.', 'category_id' => 3, 'price' => 20000, 'stock' => 40, 'image' => 'img/menu/french-fries.jpg'],
            ['name' => 'Onion Rings', 'description' => 'Crispy battered onion rings.', 'category_id' => 3, 'price' => 22000, 'stock' => 35, 'image' => 'img/menu/onion-rings.jpg'],
            ['name' => 'Chicken Nuggets', 'description' => 'Crispy chicken nuggets.', 'category_id' => 3, 'price' => 25000, 'stock' => 25, 'image' => 'img/menu/chicken-nuggets.jpg'],

            // Platter
            ['name' => 'Mix Platter', 'description' => 'A mix of snacks like fries, onion rings, and nuggets.', 'category_id' => 4, 'price' => 45000, 'stock' => 15, 'image' => 'img/menu/mix-platter.jpg'],

            // Main Course
            ['name' => 'Chicken Steak', 'description' => 'Chicken steak with black pepper sauce.', 'category_id' => 5, 'price' => 55000, 'stock' => 20, 'image' => 'img/menu/chicken-steak.jpg'],
            ['name' => 'Beef Burger', 'description' => 'Beef burger with cheese and fresh vegetables.', 'category_id' => 5, 'price' => 50000, 'stock' => 25, 'image' => 'img/menu/beef-burger.jpg'],
            ['name' => 'Spaghetti Bolognese', 'description' => 'Pasta with beef sauce.', 'category_id' => 5, 'price' => 48000, 'stock' => 30, 'image' => 'img/menu/spaghetti-bolognese.jpg'],

            // Dessert
            ['name' => 'Cheesecake', 'description' => 'Soft cheesecake with strawberry topping.', 'category_id' => 6, 'price' => 35000, 'stock' => 20, 'image' => 'img/menu/cheesecake.jpg'],
            ['name' => 'Chocolate Brownie', 'description' => 'Soft and sweet chocolate brownie.', 'category_id' => 6, 'price' => 28000, 'stock' => 25, 'image' => 'img/menu/chocolate-brownie.jpg'],
            ['name' => 'Ice Cream Sundae', 'description' => 'Vanilla ice cream with chocolate sauce.', 'category_id' => 6, 'price' => 27000, 'stock' => 30, 'image' => 'img/menu/ice-cream-sundae.jpg'],
        ]);
    }
}