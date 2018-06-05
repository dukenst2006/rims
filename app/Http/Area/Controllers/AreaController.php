<?php

namespace Rims\Http\Area\Controllers;

use Rims\Domain\Areas\Models\Area;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Areas\Resources\AreaCollection;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AreaCollection
     */
    public function index()
    {
        $areas = Area::get()->toTree();

        return new AreaCollection($areas);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Areas\Models\Area $area
     * @return \Illuminate\Http\Response
     */
    public function change(Area $area)
    {
        session()->put('area', $area->slug);

        return redirect()->route('jobs.index', $area);
    }
}
