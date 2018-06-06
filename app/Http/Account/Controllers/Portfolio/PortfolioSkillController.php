<?php

namespace Rims\Http\Account\Controllers\Portfolio;

use Rims\Domain\Levels\Models\Level;
use Rims\Domain\Portfolios\Models\PortfolioSkill;
use Rims\Domain\Portfolios\Models\Portfolio;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Portfolios\Requests\PortfolioSkillStoreRequest;
use Rims\Domain\Portfolios\Resources\PortfolioSkillCollection;
use Rims\Domain\Portfolios\Resources\PortfolioSkillResource;
use Rims\Domain\Skills\Models\Skill;

class PortfolioSkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @return PortfolioSkillCollection
     */
    public function index(Portfolio $portfolio)
    {
        $skills = $portfolio->skills;

        return new PortfolioSkillCollection(
            $skills->load(
                'level',
                'skill.ancestors'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PortfolioSkillStoreRequest $request
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @return PortfolioSkillResource
     */
    public function store(PortfolioSkillStoreRequest $request, Portfolio $portfolio)
    {
        $skill = Skill::find($request->skill_id);
        $level = Level::find($request->level_id);

        // save
        $portfolioSkill = new PortfolioSkill;
        $portfolioSkill->portfolio()->associate($portfolio);
        $portfolioSkill->level()->associate($level);
        $portfolioSkill->skill()->associate($skill);
        $portfolioSkill->save();

        return new PortfolioSkillResource($portfolioSkill->loadMissing(
            'level',
            'skill.ancestors'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @param  \Rims\Domain\Portfolios\Models\PortfolioSkill $portfolioSkill
     * @return \Illuminate\Http\Response
     */
    public function show(Portfolio $portfolio, PortfolioSkill $portfolioSkill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PortfolioSkillStoreRequest $request
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @param  \Rims\Domain\Portfolios\Models\PortfolioSkill $portfolioSkill
     * @return PortfolioSkillResource
     */
    public function update(PortfolioSkillStoreRequest $request, Portfolio $portfolio, PortfolioSkill $portfolioSkill)
    {
        $level = Level::find($request->level_id);

        // save
        $portfolioSkill->level()->associate($level);
        $portfolioSkill->save();

        return new PortfolioSkillResource($portfolioSkill->loadMissing(
            'level',
            'skill.ancestors'
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @param  \Rims\Domain\Portfolios\Models\PortfolioSkill $portfolioSkill
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Portfolio $portfolio, PortfolioSkill $portfolioSkill)
    {
        $portfolioSkill->delete();

        return response()->json(null, 204);
    }
}
