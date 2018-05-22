<?php

use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            [
                'name' => 'PHP',
                'children' => [
                    //
                ]
            ],
            [
                'name' => 'Python',
                'children' => [
                    //
                ]
            ],
            [
                'name' => 'Java',
                'children' => [
                    //
                ]
            ],
            [
                'name' => 'HTML / HTML5',
                'children' => [
                    //
                ]
            ],
            [
                'name' => 'Javascript',
                'children' => [
                    //
                ]
            ],
        ];

        foreach ($languages as $language) {
            \Rims\Domain\Languages\Models\Language::create($language);
        }
    }
}
