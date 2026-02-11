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
        $cats = [
            'Appetizers',
            'Main Course',
            'Desserts',
            'Beverages',
            'Salads',
            'Soups',
            'Seafood',
            'Vegetarian',
            'Vegan',
            'Grilled',
            'Pasta',
            'Pizza',
            'Bakery',
            'Breakfast',
            'Snacks',
            'Smoothies',
            'Coffee',
            'Tea',
            'Juices',
            'Cocktails'
        ];
        foreach ($cats as $cat) {
            Category::factory()->name($cat)->create();
        }

        Category::factory(50)->tag()->create();
    }
}
