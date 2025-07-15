<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Gender;
use App\Models\Category;
use App\Models\Product;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Gender::create(['name' => 'мужчина']);
        Gender::create(['name' => 'женщина']);

        Category::create(['name' => 'легкий']);
        Category::create(['name' => 'хрупкий']);
        Category::create(['name' => 'тяжелый']);

        $categories = Category::all();

        Product::create(['name' => 'oil', "price" => "22.12", "description" => "good oil", "quantity" => 10, "category" => $categories[1]->id]);
        Product::create(['name' => 'bread', "price" => "10.22", "description" => "best bread", "quantity" => 10, "category" => $categories[0]->id]);
        Product::create(['name' => 'toy car', "price" => "52.42", "description" => "car for playing", "quantity" => 20, "category" => $categories[1]->id]);
        Product::create(['name' => 'pen', "price" => "2.62", "description" => "ball pen", "quantity" => 30, "category" => $categories[0]->id]);
   
        $genders = Gender::all();

        User::factory()->create([
            'name'=> 'Bob',
            'email' => 'test@example.com',
            'password' => '123456',
            'admin' => 'N',
            'bonuses' => 1000,
            'gender' => $genders[0]->id
        ]);
        User::factory()->create([
            'name'=> 'Bill',
            'email' => 'test2@example.com',
            'password' => '123456',
            'admin' => 'Y',
            'bonuses' => 1000,
            'gender' => $genders[0]->id
        ]);
        User::factory()->create([
            'name'=> 'James',
            'email' => 'test3@example.com',
            'password' => '123456',
            'admin' => 'N',
            'bonuses' => 1000,
            'gender' => $genders[1]->id
        ]);
    }
}
