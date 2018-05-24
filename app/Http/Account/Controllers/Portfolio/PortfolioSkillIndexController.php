<?php

namespace Rims\Http\Account\Controllers\Portfolio;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Levels\Models\Level;
use Rims\Domain\Levels\Resources\LevelCollection;
use Rims\Domain\Skills\Models\Skill;
use Rims\Domain\Skills\Resources\SkillCollection;

class PortfolioSkillIndexController extends Controller
{
    /**
     * Display a listing of the skills.
     *
     * @return SkillCollection
     */
    public function skills()
    {
        $skills = Skill::get()->toTree();

        return new SkillCollection($skills);
    }

    /**
     * Display a listing of the skills.
     *
     * @return LevelCollection
     */
    public function levels()
    {
        $levels = Level::where('usable', true)->get();

        return new LevelCollection($levels);
    }
}
