<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::create([
            'id' => 1,
            'group_id' => 1,
            'name' => '自我發展'
        ]);

        $category = Category::create([
            'id' => 2,
            'group_id' => 1,
            'name' => '生活公約'
        ]);
    }
}
