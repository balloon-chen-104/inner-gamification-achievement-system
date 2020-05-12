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
            'content' => '這一期最高分的夥伴可以優先挑選下次的零食唷！',
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'announcement',
            'content' => '這一期前兩高分的夥伴可以獲得免費手搖飲料一杯～',
            'user_id' => 1,
            'group_id' => 1
        ]);
        

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => '陳柏綸 完成任務 假日去哪兒？當然是研討會呀, 獲得20分',
            'flash_message_switch' => 1,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => '張宇航 完成任務 假日去哪兒？當然是研討會呀, 獲得20分',
            'flash_message_switch' => 1,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => '黃仕孟 完成任務 假日去哪兒？當然是研討會呀, 獲得20分',
            'flash_message_switch' => 1,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => '劉康港 完成任務 假日去哪兒？當然是研討會呀, 獲得20分',
            'flash_message_switch' => 0,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => '曾理碩 網路新住民, 獲得30分',
            'flash_message_switch' => 0,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => '黃仕孟 網路新住民, 獲得30分',
            'flash_message_switch' => 1,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => '楊詔欽 網路新住民, 獲得30分',
            'flash_message_switch' => 1,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => '劉康港 網路新住民, 獲得30分',
            'flash_message_switch' => 0,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => '陳柏綸 十個100分！, 獲得18分',
            'flash_message_switch' => 0,
            'user_id' => 1,
            'group_id' => 1
        ]);

        $bulletin = Bulletin::create([
            'type' => 'flash_message',
            'content' => '曾理碩 十個100分！, 獲得18分',
            'flash_message_switch' => 0,
            'user_id' => 1,
            'group_id' => 1
        ]);
    }
}
