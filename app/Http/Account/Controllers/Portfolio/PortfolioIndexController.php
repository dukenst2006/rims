<?php

namespace Rims\Http\Account\Controllers\Portfolio;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Portfolios\Resources\PortfolioCollection;

class PortfolioIndexController extends Controller
{
    /**
     * Display a listing of portfolios.
     *
     * @param Request $request
     * @return PortfolioCollection
     */
    public function index(Request $request)
    {
        $portfolios = $request->user()->portfolios;

        return new PortfolioCollection(
            $portfolios->load(
                'uploads',
                'skills.level',
                'skills.skill.ancestors'
            )
        );
    }
}
