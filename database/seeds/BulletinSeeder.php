<?php

use Illuminate\Database\Seeder;
use App\Bulletin;

class BulletinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bulletin = Bulletin::create([
            'type' => 'announcement',
            'content' => '公告 A',
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'announcement',
            'content' => '公告 B',
            'user_id' => 1,
            'group_id' => 1
        ]);
        

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => 'content A',
            'flash_message_switch' => 1,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => 'content B',
            'flash_message_switch' => 1,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => 'content C',
            'flash_message_switch' => 1,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => 'content D',
            'flash_message_switch' => 0,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => 'content E',
            'flash_message_switch' => 0,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => 'content F',
            'flash_message_switch' => 1,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => 'content G',
            'flash_message_switch' => 1,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => 'content H',
            'flash_message_switch' => 0,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => 'content I',
            'flash_message_switch' => 0,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => 'content J',
            'flash_message_switch' => 0,
            'user_id' => 1,
            'group_id' => 1
        ]);
    }
}
