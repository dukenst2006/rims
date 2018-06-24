<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 6/24/2018
 * Time: 3:24 AM
 */

namespace Rims\Domain\Categories\Filters;

use Illuminate\Database\Eloquent\Builder;
use Rims\App\Filters\FilterAbstract;
use Rims\Domain\Categories\Models\Category;

class CategoriesFilter extends FilterAbstract
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

        $category = Category::with(['descendants'])
            ->where('slug', $value)->first();

        $categories = $category->descendants->pluck('id')->prepend($category->id);

        return $builder->whereHas('categories', function (Builder $builder) use ($categories) {
            $builder->whereIn('category_id', $categories);
        });
    }
}