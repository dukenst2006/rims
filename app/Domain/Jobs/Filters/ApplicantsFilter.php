<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 6/6/2018
 * Time: 12:06 AM
 */

namespace Rims\Domain\Jobs\Filters;

use Illuminate\Database\Eloquent\Builder;
use Rims\App\Filters\FilterAbstract;

class ApplicantsFilter extends FilterAbstract
{
    /**
     * Database value mappings.
     *
     * @return array
     */
    public function mappings()
    {
        return [
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'more' => 4,
        ];
    }

    /**
     * Database operator mappings.
     *
     * @return array
     */
    public function operators()
    {
        return [
            'one' => '=',
            'two' => '=',
            'three' => '=',
            'more' => '>=',
        ];
    }

    /**
     * Apply filter.
     *
     * @param Builder $builder
     * @param $value
     *
     * @return mixed
     */
    public function filter(Builder $builder, $value)
    {
        if ($value === null) {
            return $builder;
        }

        return $builder->where('applicants',
            $this->resolveFilterOperator($value),
            $this->resolveFilterValue($value)
        );
    }
}