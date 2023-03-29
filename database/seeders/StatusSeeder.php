<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create([
            'title' => 'To Do',
            'slug' => 'to-do',
            'order' => 1,
        ]);

        Status::create([
            'title' => 'In Progress',
            'slug' => 'in-progress',
            'order' => 2,
        ]);

        Status::create([
            'title' => 'Done',
            'slug' => 'done',
            'order' => 3,
        ]);
    }
}
