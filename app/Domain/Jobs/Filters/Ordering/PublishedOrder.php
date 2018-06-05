<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 6/5/2018
 * Time: 11:58 PM
 */

namespace Rims\Domain\Jobs\Filters\Ordering;

use Illuminate\Database\Eloquent\Builder;
use Rims\App\Filters\FilterAbstract;

class PublishedOrder extends FilterAbstract
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
        return $builder->orderBy('published_at', $this->resolveOrderDirection($value));
    }
}