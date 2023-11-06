<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = ['Бэкэнд','Фронтенд'];

        foreach($projects as $project){
            Project::create([
                'name' => $project
            ]);
        }
    }
}
