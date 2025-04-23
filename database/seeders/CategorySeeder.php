<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {     
        Category::create(['name' => 'легкий']);
        Category::create(['name' => 'хрупкий']);
        Category::create(['name' => 'тяжелый']);

        // DB::table('categories')->insert([
        //     'name' => "легкий"          
        // ]);
        // DB::table('categories')->insert([
        //     'name' => "хрупкий"          
        // ]);
        // DB::table('categories')->insert([
        //     'name' => "тяжелый"          
        // ]);
    }
}
