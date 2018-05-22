<?php

use Illuminate\Database\Seeder;

class EducationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name' => 'PhD',
            ],
            [
                'name' => 'Master\'s',
            ],
            [
                'name' => 'Undergraduate',
            ],
            [
                'name' => 'Certificate',
            ],
            [
                'name' => 'High School',
            ],
        ];

        foreach ($items as $item) {
            \Rims\Domain\Education\Models\Education::create($item);
        }
    }
}
