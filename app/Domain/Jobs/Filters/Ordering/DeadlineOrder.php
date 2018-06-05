<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 6/6/2018
 * Time: 12:01 AM
 */

namespace Rims\Domain\Jobs\Filters\Ordering;

use Illuminate\Database\Eloquent\Builder;
use Rims\App\Filters\FilterAbstract;

class DeadlineOrder extends FilterAbstract
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
        return $builder->orderBy('closed_at', $this->resolveOrderDirection($value));
    }
}