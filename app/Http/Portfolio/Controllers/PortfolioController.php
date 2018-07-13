<?php

namespace Rims\Http\Portfolio\Controllers;

use Rims\Domain\Portfolios\Models\Portfolio;
use Rims\Domain\Users\Models\User;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Rims\Domain\Users\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $user->load('skills.skill');

        $portfolios = $user->portfolios()->finished()->isLive()->orderByDesc('created_at')->paginate();

        return view('portfolios.index', compact('user', 'portfolios'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Users\Models\User $user
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Portfolio $portfolio)
    {
        $uploads = $portfolio->uploads()->get();

        $nextPortfolio = $user->portfolios()->finished()->isLive()
            ->where('id', '<', $portfolio->id)
            ->orderByDesc('created_at')->first();

        $previousPortfolio = $user->portfolios()->finished()->isLive()
            ->where('id', '>', $portfolio->id)
            ->orderByDesc('created_at')->first();

        return view('portfolios.show',
            compact('user', 'portfolio', 'uploads', 'previousPortfolio', 'nextPortfolio')
        );
    }
}
