<?php

use Illuminate\Database\Seeder;
use App\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $task1 = Task::create([
            'id' => 1,
            'category_id' => 1,
            'creator_id' => 1,
            'name' => '假日去哪兒？當然是研討會呀',
            'description' => '參與PHP研討會。請檢附報名或相關文件',
            'score' => 20,
            'remain_times' => 3,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('-1 days'))),
            'confirmed' => 1
        ]);

        $task2 = Task::create([
            'id' => 2,
            'category_id' => 1,
            'creator_id' => 1,
            'name' => '就是愛閱讀',
            'description' => '閱讀PHP相關書籍。準備5~10分鐘的簡報或2頁A4心得',
            'score' => 25,
            'remain_times' => 3,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task3 = Task::create([
            'id' => 3,
            'category_id' => 1,
            'creator_id' => 1,
            'name' => '網路新住民',
            'description' => '觀看PHP相關線上課程。準備5~10分鐘的簡報或2頁A4心得',
            'score' => 30,
            'remain_times' => 3,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task4 = Task::create([
            'id' => 4,
            'category_id' => 1,
            'creator_id' => 1,
            'name' => '十個100分！',
            'description' => 'Codewars刷題達1000分。分數截圖上傳至Teams',
            'score' => 18,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task5 = Task::create([
            'id' => 5,
            'category_id' => 1,
            'creator_id' => 1,
            'name' => '有福同享',
            'description' => '分享資源給同事。基於互信原則，同事口頭證明即可',
            'score' => 16,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('-1 days'))),
            'confirmed' => 1
        ]);

        $task6 = Task::create([
            'id' => 6,
            'category_id' => 1,
            'creator_id' => 1,
            'name' => '蟲蟲危機',
            'description' => '幫助同事解BUG。基於互信原則，同事口頭證明即可',
            'score' => 22,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task7 = Task::create([
            'id' => 7,
            'category_id' => 2,
            'creator_id' => 1,
            'name' => '越整齊越好吃',
            'description' => '整理零食櫃。整理後的櫃子拍照上傳至Teams',
            'score' => 15,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task8 = Task::create([
            'id' => 8,
            'category_id' => 2,
            'creator_id' => 1,
            'name' => '讓垃圾消失吧',
            'description' => '撿垃圾。自由心證，無需提供證明',
            'score' => 15,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task9 = Task::create([
            'id' => 9,
            'category_id' => 2,
            'creator_id' => 1,
            'name' => '跟新的一樣',
            'description' => '整理辦公桌周邊環境。提供整理前後對比照片上傳至Teams',
            'score' => 15,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task10 = Task::create([
            'id' => 10,
            'category_id' => 2,
            'creator_id' => 1,
            'name' => '早起的鳥兒有蟲吃',
            'description' => '連續一週九點前抵達公司。請檢附打卡記錄',
            'score' => 15,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('-1 days'))),
            'confirmed' => 1
        ]);

        $task11 = Task::create([
            'id' => 11,
            'category_id' => 2,
            'creator_id' => 1,
            'name' => '守門員',
            'description' => '連續一週下班幫忙拿鑰匙及鎖門。基於互信原則，同事口頭證明即可',
            'score' => 15,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task1->users()->attach(6, ['confirmed' => 1]);
        $task1->users()->attach(5, ['confirmed' => -1]);
        $task1->users()->attach(4, ['confirmed' => 1]);
        $task1->users()->attach(3, ['confirmed' => 1]);
        $task1->users()->attach(2, ['confirmed' => 0]);
        $task1->users()->attach(1, ['confirmed' => 1]);

        // $task2->users()->attach(6, ['confirmed' => 0]);
        // $task2->users()->attach(5, ['confirmed' => 0]);
        // $task2->users()->attach(4, ['confirmed' => 1]);
        // $task2->users()->attach(3, ['confirmed' => 0]);
        // $task2->users()->attach(2, ['confirmed' => 1]);
        // $task2->users()->attach(1, ['confirmed' => 1]);

        $task3->users()->attach(6, ['confirmed' => -1]);
        $task3->users()->attach(5, ['confirmed' => 1]);
        $task3->users()->attach(4, ['confirmed' => 0]);
        $task3->users()->attach(3, ['confirmed' => 1]);
        $task3->users()->attach(2, ['confirmed' => 1]);
        $task3->users()->attach(1, ['confirmed' => 1]);

        $task4->users()->attach(6, ['confirmed' => 1]);
        $task4->users()->attach(5, ['confirmed' => 1]);
        $task4->users()->attach(4, ['confirmed' => 1]);
        $task4->users()->attach(3, ['confirmed' => 1]);
        $task4->users()->attach(2, ['confirmed' => 1]);
        $task4->users()->attach(1, ['confirmed' => 1]);

        // $task5->users()->attach(6, ['confirmed' => 0]);
        // $task5->users()->attach(5, ['confirmed' => 1]);
        // $task5->users()->attach(4, ['confirmed' => 0]);
        // $task5->users()->attach(3, ['confirmed' => 1]);
        // $task5->users()->attach(2, ['confirmed' => 1]);
        // $task5->users()->attach(1, ['confirmed' => 0]);

        $task6->users()->attach(6, ['confirmed' => -1]);
        $task6->users()->attach(5, ['confirmed' => 1]);
        $task6->users()->attach(4, ['confirmed' => 1]);
        $task6->users()->attach(3, ['confirmed' => 0]);
        $task6->users()->attach(2, ['confirmed' => 1]);
        $task6->users()->attach(1, ['confirmed' => 1]);

        $task7->users()->attach(6, ['confirmed' => 1]);
        $task7->users()->attach(5, ['confirmed' => 1]);
        $task7->users()->attach(4, ['confirmed' => 1]);
        $task7->users()->attach(3, ['confirmed' => -1]);
        $task7->users()->attach(2, ['confirmed' => -1]);
        $task7->users()->attach(1, ['confirmed' => 1]);

        $task8->users()->attach(6, ['confirmed' => 1]);
        $task8->users()->attach(5, ['confirmed' => 1]);
        $task8->users()->attach(4, ['confirmed' => -1]);
        $task8->users()->attach(3, ['confirmed' => 1]);
        $task8->users()->attach(2, ['confirmed' => 1]);
        $task8->users()->attach(1, ['confirmed' => 1]);

        // $task9->users()->attach(6, ['confirmed' => 0]);
        // $task9->users()->attach(5, ['confirmed' => 0]);
        // $task9->users()->attach(4, ['confirmed' => 0]);
        // $task9->users()->attach(3, ['confirmed' => 0]);
        // $task9->users()->attach(2, ['confirmed' => 0]);
        // $task9->users()->attach(1, ['confirmed' => 0]);

        $task10->users()->attach(6, ['confirmed' => 1]);
        $task10->users()->attach(5, ['confirmed' => 0]);
        $task10->users()->attach(4, ['confirmed' => 0]);
        $task10->users()->attach(3, ['confirmed' => 0]);
        $task10->users()->attach(2, ['confirmed' => 0]);
        $task10->users()->attach(1, ['confirmed' => 1]);

        $task11->users()->attach(6, ['confirmed' => 0]);
        $task11->users()->attach(5, ['confirmed' => 1]);
        $task11->users()->attach(4, ['confirmed' => -1]);
        $task11->users()->attach(3, ['confirmed' => 1]);
        $task11->users()->attach(2, ['confirmed' => -1]);
        $task11->users()->attach(1, ['confirmed' => 1]);
    }
}
