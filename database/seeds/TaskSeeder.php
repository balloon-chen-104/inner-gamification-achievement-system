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
            'name' => 'A',
            'description' => 'description A',
            'score' => 20,
            'remain_times' => 3,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('-1 days'))),
            'confirmed' => 1
        ]);

        $task2 = Task::create([
            'id' => 2,
            'category_id' => 1,
            'creator_id' => 1,
            'name' => 'B',
            'description' => 'description B',
            'score' => 25,
            'remain_times' => 3,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task3 = Task::create([
            'id' => 3,
            'category_id' => 1,
            'creator_id' => 1,
            'name' => 'C',
            'description' => 'description C',
            'score' => 30,
            'remain_times' => 3,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task4 = Task::create([
            'id' => 4,
            'category_id' => 1,
            'creator_id' => 1,
            'name' => 'D',
            'description' => 'description D',
            'score' => 18,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task5 = Task::create([
            'id' => 5,
            'category_id' => 1,
            'creator_id' => 1,
            'name' => 'E',
            'description' => 'description E',
            'score' => 16,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('-1 days'))),
            'confirmed' => 1
        ]);

        $task6 = Task::create([
            'id' => 6,
            'category_id' => 1,
            'creator_id' => 1,
            'name' => 'F',
            'description' => 'description F',
            'score' => 22,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task7 = Task::create([
            'id' => 7,
            'category_id' => 2,
            'creator_id' => 1,
            'name' => 'G',
            'description' => 'description G',
            'score' => 15,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task8 = Task::create([
            'id' => 8,
            'category_id' => 2,
            'creator_id' => 1,
            'name' => 'H',
            'description' => 'description H',
            'score' => 15,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task9 = Task::create([
            'id' => 9,
            'category_id' => 2,
            'creator_id' => 1,
            'name' => 'I',
            'description' => 'description I',
            'score' => 15,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('1 days'))),
            'confirmed' => 1
        ]);

        $task10 = Task::create([
            'id' => 10,
            'category_id' => 2,
            'creator_id' => 1,
            'name' => 'J',
            'description' => 'description J',
            'score' => 15,
            'remain_times' => 6,
            'expired_at' => date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string(('-1 days'))),
            'confirmed' => 1
        ]);

        $task11 = Task::create([
            'id' => 11,
            'category_id' => 2,
            'creator_id' => 1,
            'name' => 'K',
            'description' => 'description K',
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
