<?php

namespace Rims\Http\Skill\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Skills\Models\Skill;
use Rims\Domain\Skills\Resources\SkillCollection;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return SkillCollection
     */
    public function index()
    {
        $skills = Skill::get()->toTree();

        return new SkillCollection($skills);
    }
}
