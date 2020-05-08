<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = Setting::create([
            'id' => 1,
            'cycle' => 20,
            'started_at' => date('Y-m-d'),
            'group_id' => 1
        ]);
    }
}
