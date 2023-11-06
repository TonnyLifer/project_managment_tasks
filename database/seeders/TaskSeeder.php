<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks_cards = [
            ['Взаимовействие'],
            ['resful api'],
            ['роуты'],
            ['сесии или токены'],
            ['дебажить'],

            ['Стили'],
            ['Разрешения'],
            ['Лэйауты']
        ];

        foreach($tasks_cards as $key => $tasks){
            foreach($tasks as $task){
                Task::create([
                    'text' => $task,
                    'card_id' => $key+1
                ]);
            }
            
        }
    }
}
