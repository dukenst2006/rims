<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 6/6/2018
 * Time: 4:29 PM
 */

namespace Rims\Domain\Education\Filters;

use Illuminate\Database\Eloquent\Builder;
use Rims\App\Filters\FilterAbstract;
use Rims\Domain\Education\Models\Education;

class EducationFilter extends FilterAbstract
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

        $education = Education::where('slug', $value)->first();

        $builder->whereHas('education', function (Builder $builder) use ($education) {
            $builder->where('education_id', $education->id);
        });
    }
}