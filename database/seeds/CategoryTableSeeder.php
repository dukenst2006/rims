<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Jobs',
                'usable' => false,
                'children' => [
                    [
                        'name' => 'Standard',
                        'usable' => true
                    ],
                    [
                        'name' => 'Advanced',
                        'usable' => false,
                        'children' => [
                            [
                                'name' => 'Featured',
                                'usable' => true
                            ],
                            [
                                'name' => 'Premium',
                                'usable' => true
                            ],
                            [
                                'name' => 'Urgent',
                                'usable' => true
                            ],
                        ]
                    ],
                ]
            ]
        ];

        foreach ($categories as $category) {
            \Rims\Domain\Categories\Models\Category::create($category);
        }

    }
}
