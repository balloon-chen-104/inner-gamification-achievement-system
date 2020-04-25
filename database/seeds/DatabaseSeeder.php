<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        // factory(\App\User::class, 3)->create();
        factory(\App\Task::class, 10)->create();
    }
}
