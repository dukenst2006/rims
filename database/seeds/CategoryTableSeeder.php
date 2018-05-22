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
                'name' => 'Education',
                'children' => [
                    //
                ],
            ],
            [
                'name' => 'Computer Hardware',
                'children' => [
                    //
                ],
            ],
            [
                'name' => 'Computer Software',
                'children' => [
                    //
                ],
            ],
            [
                'name' => 'Computer Services',
                'children' => [
                    //
                ],
            ],
            [
                'name' => 'Internet',
                'children' => [
                    ['name' => 'E-commerce'],
                    ['name' => 'Web Hosting'],
                    ['name' => 'Chat / Messaging'],
                    ['name' => 'Search'],
                    ['name' => 'SEO'],
                    ['name' => 'Marketing'],
                    ['name' => 'Payments'],
                    ['name' => 'Web Development'],
                ],
            ],
            [
                'name' => 'Graphics',
                'children' => [
                    //
                ],
            ],
            [
                'name' => 'Technology',
                'children' => [
                    ['name' => 'Mobile Payment'],
                    ['name' => 'Biotech'],
                    ['name' => 'Mobile Development'],
                ],
            ],
            [
                'name' => 'Media',
                'children' => [
                    //
                ],
            ],
            [
                'name' => 'Real Estate & Housing',
                'children' => [
                    ['name' => 'Land / Plots'],
                    ['name' => 'Apartment Rental'],
                    ['name' => 'House Rental'],
                    ['name' => 'Residential Construction'],
                    ['name' => 'Mortgage Services'],
                    ['name' => 'Property Insurance'],
                ],
            ],
            [
                'name' => 'Car Rental',
                'children' => [
                    //
                ],
            ],
            [
                'name' => 'Logistics',
                'children' => [
                    //
                ],
            ],
            [
                'name' => 'Tourism',
                'children' => [
                    //
                ],
            ],
            [
                'name' => 'Hospitality',
                'children' => [
                    //
                ],
            ],
            [
                'name' => 'Food',
                'children' => [
                    ['name' => 'Restaurant'],
                    ['name' => 'Fast Food Chain'],
                    ['name' => 'Bakery'],
                    ['name' => 'Winery'],
                ],
            ],
        ];

        foreach ($categories as $category) {
            \Rims\Domain\Categories\Models\Category::create($category);
        }

    }
}
