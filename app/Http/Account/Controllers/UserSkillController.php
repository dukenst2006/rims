<?php

namespace Rims\Http\Account\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Account\Requests\UserSkillStoreRequest;
use Rims\Domain\Levels\Models\Level;
use Rims\Domain\Skills\Models\Skill;
use Rims\Domain\Users\Models\UserSkill;

class UserSkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account.skills.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserSkillStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserSkillStoreRequest $request)
    {
        $skill = Skill::find($request->skill_id);
        $level = Level::find($request->level_id);

        $userSkill = new UserSkill;
        $userSkill->skill()->associate($skill);
        $userSkill->level()->associate($level);
        $userSkill->user()->associate($request->user());
        $userSkill->save();

        return response()->json(null, 204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserSkillStoreRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserSkillStoreRequest $request, $id)
    {
        $skill = Skill::find($request->skill_id);
        $level = Level::find($request->level_id);

        $userSkill = $request->user()->skills()->where('id', $id)->firstOrFail();
        $userSkill->skill()->associate($skill);
        $userSkill->level()->associate($level);
        $userSkill->save();

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->user()->skills()->where('id', $id)->delete();

        return response()->json(null, 204);
    }
}
