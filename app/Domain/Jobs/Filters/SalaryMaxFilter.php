<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 6/6/2018
 * Time: 3:17 PM
 */

namespace Rims\Domain\Jobs\Filters;

use Illuminate\Database\Eloquent\Builder;
use Rims\App\Filters\FilterAbstract;

class SalaryMaxFilter extends FilterAbstract
{
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

        return $builder->where('salary_max', '<=', $value);
    }
}