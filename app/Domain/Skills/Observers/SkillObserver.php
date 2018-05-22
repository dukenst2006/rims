<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 5/11/2018
 * Time: 4:02 PM
 */

namespace Rims\Domain\Skills\Observers;

use Rims\Domain\Skills\Models\Skill;

class SkillObserver
{
    /**
     * Listen to resource 'creating' event.
     *
     * @param Skill $skill
     */
    public function creating(Skill $skill)
    {
        $prefix = $skill->parent ? $skill->parent->name . ' ' : '';
        $skill->slug = str_slug($prefix . $skill->name);

        $skill->usable = $skill->parent ? true : false;
    }
}