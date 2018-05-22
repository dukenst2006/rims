<?php

namespace Rims\Http\Job\Controllers;

use Rims\Domain\Areas\Models\Area;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Area $area
     * @return \Illuminate\Http\Response
     */
    public function index(Area $area)
    {
        return view('jobs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Areas\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        //
    }
}
