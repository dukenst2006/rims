<?php

use Illuminate\Database\Seeder;

class LevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = [
            [
                'name' => 'Beginner',
            ],
            [
                'name' => 'Intermediate',
            ],
            [
                'name' => 'Advanced',
            ],
        ];

        foreach ($levels as $level) {
            \Rims\Domain\Levels\Models\Level::create($level);
        }
    }
}
