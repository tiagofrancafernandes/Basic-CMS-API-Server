<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        static::manualRun(10);
    }

    /**
     * function manualRun
     *
     * @param ?int $count
     * @return void
     */
    public static function manualRun(?int $count = 10): void
    {
        Category::factory()->count($count ?: 10)->create();
    }

    /**
     * function randomOrCreate
     *
     * @param
     * @return Category
     */
    public static function randomOrCreate(): Category
    {
        return Category::inRandomOrder()->first() ?? Category::factory(5)->create()->random();
    }
}
