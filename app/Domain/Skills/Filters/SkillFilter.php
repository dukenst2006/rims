<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 6/6/2018
 * Time: 5:24 PM
 */

namespace Rims\Domain\Skills\Filters;

use Illuminate\Database\Eloquent\Builder;
use Rims\App\Filters\FilterAbstract;
use Rims\Domain\Skills\Models\Skill;

class SkillFilter extends FilterAbstract
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

        $values = explode('&', $value);

        $skills = Skill::with(['descendants'])
            ->whereIn('slug', $values)->get();

        $skills = $skills->map(function ($skill, $key) {
            return $skill->descendants->pluck('id')->prepend($skill->id);
        })->toArray();

        return $builder->whereHas('skills', function (Builder $builder) use ($skills) {
            $builder->whereIn('skill_id', $skills);
        });
    }
}