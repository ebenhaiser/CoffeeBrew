<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'Coffee',
                'slug' => 'coffee',
            ],
            [
                'name' => 'Non-Coffee',
                'slug' => 'non-coffee',
            ],
            [
                'name' => 'Snack',
                'slug' => 'snack',
            ],
            [
                'name' => 'Plater',
                'slug' => 'plater',
            ],
            [
                'name' => 'Main Course',
                'slug' => 'main-course',
            ],
            [
                'name' => 'Dessert',
                'slug' => 'dessert',
            ],
        ]);
    }
}
