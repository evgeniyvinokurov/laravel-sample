<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        Product::create(['name' => 'oil', "price" => "22.12", "description" => "good oil", "quantity" => 10, "category" => $categories[1]->id]);
        Product::create(['name' => 'bread', "price" => "10.22", "description" => "best bread", "quantity" => 10, "category" => $categories[0]->id]);
        Product::create(['name' => 'toy car', "price" => "52.42", "description" => "car for playing", "quantity" => 20, "category" => $categories[1]->id]);
        Product::create(['name' => 'pen', "price" => "2.62", "description" => "ball pen", "quantity" => 30, "category" => $categories[0]->id]);
    }
}
