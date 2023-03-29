<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 6; $i++) {
            Task::insert([
                'title' => 'Task ' . $i,
                'description' => 'Description ' . $i,
                'status_id' => 1,
                'order' => $i,
            ]);
        }

        for ($i = 6; $i < 11; $i++) {
            Task::insert([
                'title' => 'Task ' . $i,
                'description' => 'Description ' . $i,
                'status_id' => 2,
                'order' => $i,
            ]);
        }

        for ($i = 11; $i < 16; $i++) {
            Task::insert([
                'title' => 'Task ' . $i,
                'description' => 'Description ' . $i,
                'status_id' => 3,
                'order' => $i,
            ]);
        }
    }
}
