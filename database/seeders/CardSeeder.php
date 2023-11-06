<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cards_projects = [
            ['Создание архитектуры','Написать api','Взаимодействие с бд','Авторизация','Тесты'],
            ['Разработка анимации','Адаптивный интерфейс','Общий дизайн']
        ];

        foreach($cards_projects as $key => $cards){
            foreach($cards as $card){
                Card::create([
                    'name' => $card,
                    'project_id' => $key+1
                ]);
            }
        }
    }
}
