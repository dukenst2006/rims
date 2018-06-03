<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Areas\Models\Area;
use Rims\Domain\Areas\Resources\AreaCollection;
use Rims\Domain\Jobs\Models\Job;
use Rims\Domain\Jobs\Resources\JobCollection;

class JobIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JobCollection
     */
    public function index()
    {
        $jobs = Job::with('area.ancestors')->latestFirst()->finished()->get();

        return new JobCollection($jobs);
    }

    /**
     * Display a listing of the resource.
     *
     * @return AreaCollection
     */
    public function areas()
    {
        $areas = Area::get()->toTree();

        return new AreaCollection($areas);
    }
}
