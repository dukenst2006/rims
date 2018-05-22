<?php

namespace Rims\Http\Account\Controllers\Portfolio;

use Rims\Domain\Account\Requests\UserPortfolioStoreRequest;
use Rims\Domain\Portfolios\Models\Portfolio;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Portfolios\Resources\PortfolioResource;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account.portfolios.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return PortfolioResource
     */
    public function create(Request $request)
    {
        $portfolio = $request->user()->portfolios()->create([
            'title' => 'Untitled',
            'overview_short' => 'None',
            'overview' => 'None',
            'price' => 0,
            'live' => false,
            'approved' => false,
            'finished' => false,
        ]);

        return new PortfolioResource($portfolio);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserPortfolioStoreRequest $request
     * @param Portfolio $portfolio
     * @return PortfolioResource
     */
    public function store(UserPortfolioStoreRequest $request, Portfolio $portfolio)
    {
        //save
        $portfolio->fill($request->only(['title', 'overview_short', 'overview']));
        $portfolio->finished = true;
        $portfolio->save();

        return new PortfolioResource($portfolio);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @return \Illuminate\Http\Response
     */
    public function show(Portfolio $portfolio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserPortfolioStoreRequest $request
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @return \Illuminate\Http\Response
     */
    public function update(UserPortfolioStoreRequest $request, Portfolio $portfolio)
    {
        //update
        $portfolio->update($request->only(['title', 'overview_short', 'overview']));

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();

        return response()->json(null, 204);
    }
}
