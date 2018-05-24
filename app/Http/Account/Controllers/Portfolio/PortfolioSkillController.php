<?php

namespace Rims\Http\Account\Controllers\Portfolio;

use Rims\Domain\Levels\Models\Level;
use Rims\Domain\Portfolios\Models\PortfolioSkillable;
use Rims\Domain\Portfolios\Models\Portfolio;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Portfolios\Requests\PortfolioSkillStoreRequest;
use Rims\Domain\Portfolios\Resources\PortfolioSkillableCollection;
use Rims\Domain\Portfolios\Resources\PortfolioSkillableResource;
use Rims\Domain\Skills\Models\Skill;

class PortfolioSkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @return PortfolioSkillableCollection
     */
    public function index(Portfolio $portfolio)
    {
        $skills = $portfolio->skills;

        return new PortfolioSkillableCollection(
            $skills->load(
                'level',
                'skillable.ancestors'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PortfolioSkillStoreRequest $request
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @return PortfolioSkillableResource
     */
    public function store(PortfolioSkillStoreRequest $request, Portfolio $portfolio)
    {
        $skill = Skill::find($request->skill_id);
        $level = Level::find($request->level_id);

        // save
        $portfolioSkill = new PortfolioSkillable;
        $portfolioSkill->portfolio()->associate($portfolio);
        $portfolioSkill->level()->associate($level);
        $portfolioSkill->skillable()->associate($skill);
        $portfolioSkill->save();

        return new PortfolioSkillableResource($portfolioSkill->loadMissing(
            'level',
            'skillable.ancestors'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @param  \Rims\Domain\Portfolios\Models\PortfolioSkillable $portfolioSkill
     * @return \Illuminate\Http\Response
     */
    public function show(Portfolio $portfolio, PortfolioSkillable $portfolioSkill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PortfolioSkillStoreRequest $request
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @param  \Rims\Domain\Portfolios\Models\PortfolioSkillable $portfolioSkill
     * @return PortfolioSkillableResource
     */
    public function update(PortfolioSkillStoreRequest $request, Portfolio $portfolio, PortfolioSkillable $portfolioSkill)
    {
        $level = Level::find($request->level_id);

        // save
        $portfolioSkill->level()->associate($level);
        $portfolioSkill->save();

        return new PortfolioSkillableResource($portfolioSkill->loadMissing(
            'level',
            'skillable.ancestors'
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @param  \Rims\Domain\Portfolios\Models\PortfolioSkillable $portfolioSkill
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Portfolio $portfolio, PortfolioSkillable $portfolioSkill)
    {
        $portfolioSkill->delete();

        return response()->json(null, 204);
    }
}
