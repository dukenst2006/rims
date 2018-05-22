<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 5/11/2018
 * Time: 4:04 PM
 */

namespace Rims\App\ViewComposers;

use Illuminate\View\View;
use Rims\Domain\Skills\Models\Skill;

class SkillsComposer
{
    /**
     * Holds a list of skills from storage.
     *
     * @var
     */
    private $skills;

    /**
     * @param View $view
     * @return mixed
     */
    public function compose(View $view)
    {
        if(!$this->skills) {
            $this->skills = Skill::get()->toTree();
        }

        return $view->with('skills', $this->skills);
    }
}