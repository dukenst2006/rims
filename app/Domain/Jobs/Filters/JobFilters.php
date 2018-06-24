<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 6/5/2018
 * Time: 11:45 PM
 */

namespace Rims\Domain\Jobs\Filters;

use Rims\App\Filters\FiltersAbstract;
use Rims\Domain\Categories\Filters\CategoriesFilter;
use Rims\Domain\Education\Filters\EducationFilter;
use Rims\Domain\Jobs\Filters\Ordering\DeadlineOrder;
use Rims\Domain\Jobs\Filters\Ordering\PublishedOrder;
use Rims\Domain\Skills\Filters\SkillFilter;

class JobFilters extends FiltersAbstract
{
    protected $filters = [
        'published' => PublishedOrder::class,
        'deadline' => DeadlineOrder::class,
        'applicants' => ApplicantsFilter::class,
        'type' => TypeFilter::class,
        'location' => LocationFilter::class,
        'salary_min' => SalaryMinFilter::class,
        'salary_max' => SalaryMaxFilter::class,
        'education' => EducationFilter::class,
        'skills' => SkillFilter::class,
        'categories' => CategoriesFilter::class,
    ];

    protected $defaultFilters = [
        'published' => 'desc'
    ];

    /**
     * Create project filters map.
     *
     * @return array
     */
    public static function mappings()
    {
        //use view composer to render categories / education / skills / languages

        $map = [
            'location' => [
                'map' => [
                    'fixed' => 'Fixed / On site',
                    'remote' => 'Remote / Off site',
                ],
                'heading' => 'Job location',
                'style' => 'radio'
            ],
            'type' => [
                'map' => [
                    'full' => 'Full time',
                    'part' => 'Part time',
                ],
                'heading' => 'Job type',
                'style' => 'radio'
            ],
            'applicants' => [
                'map' => [
                    'one' => 'One',
                    'two' => 'Two',
                    'three' => 'Three',
                    'more' => '4 or more',
                ],
                'heading' => 'Applicants',
                'style' => 'radio'
            ],
            'published' => [
                'map' => [
                    'desc' => 'Latest',
                    'asc' => 'Older'
                ],
                'heading' => 'Order by post time',
                'style' => 'list'
            ],
            'deadline' => [
                'map' => [
                    'desc' => 'Near',
                    'asc' => 'Far'
                ],
                'heading' => 'Order by deadline',
                'style' => 'list'
            ],
        ];

        //auth only filters
        if (auth()->check()) {
            $auth_map = [
            ];

            $map = array_merge($map, $auth_map);
        }

        return $map;
    }
}