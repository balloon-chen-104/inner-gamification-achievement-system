<?php

use Illuminate\Database\Seeder;
use App\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group = Group::create([
            'id' => 1,
            'creator_id' => 1,
            'name' => '小鮮肉',
            'description' => '這裡是小鮮肉群組～'
        ]);

        $group->users()->attach(1, ['authority' => 1]);
        $group->users()->attach(2, ['authority' => 0]);
        $group->users()->attach(3, ['authority' => 0]);
        $group->users()->attach(4, ['authority' => 0]);
        $group->users()->attach(5, ['authority' => 0]);
        $group->users()->attach(6, ['authority' => 0]);
    }
}
