<?php

namespace Rims\Http\Account\Controllers\Portfolio;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Portfolios\Models\Portfolio;

class PortfolioStatusController extends Controller
{
    /**
     * Update resource status in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus(Request $request, Portfolio $portfolio)
    {
        $portfolio->update($request->only(['live']));

        return response()->json(null, 204);
    }
}
