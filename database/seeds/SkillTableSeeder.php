<?php

use Illuminate\Database\Seeder;

class SkillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = [
            [
                'name' => 'Web Developer',
                'children' => [
                    [
                        'name' => 'Frontend Developer',
                        'children' => [
                            [
                                'name' => 'UI designer'
                            ],
                            [
                                'name' => 'UX designer'
                            ],
                        ]
                    ],
                    [
                        'name' => 'Backend Developer',
                        'children' => [
                            [
                                'name' => 'API'
                            ],
                            [
                                'name' => 'Database'
                            ],
                            [
                                'name' => 'Server'
                            ],
                        ]
                    ],
                    [
                        'name' => 'Fullstack Developer',
                        'children' => [
                            //
                        ]
                    ],
                ],
            ],
            [
                'name' => 'Mobile Developer',
                'children' => [
                    [
                        'name' => 'Android Developer',
                        'children' => [
                            [
                                'name' => 'UI designer'
                            ],
                            [
                                'name' => 'UX designer'
                            ],
                            [
                                'name' => 'Fullstack Developer',
                            ],
                        ]
                    ],
                    [
                        'name' => 'iOs Developer',
                        'children' => [
                            [
                                'name' => 'UI designer'
                            ],
                            [
                                'name' => 'UX designer'
                            ],
                            [
                                'name' => 'Fullstack Developer',
                            ],
                        ]
                    ],
                ],
            ],
            [
                'name' => 'Graphics',
                'children' => [
                    [
                        'name' => 'Logo Designer',
                    ],
                    [
                        'name' => 'UI Kit Designer',
                    ],
                    [
                        'name' => 'Mockup Designer',
                    ],
                ]
            ],
        ];

        foreach ($skills as $skill) {
            \Rims\Domain\Skills\Models\Skill::create($skill);
        }
    }
}
