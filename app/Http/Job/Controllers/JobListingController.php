<?php

namespace Rims\Http\Job\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Areas\Models\Area;
use Rims\Domain\Jobs\Models\Job;
use Rims\Domain\Jobs\Resources\JobCollection;

class JobListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Area $area
     * @return JobCollection
     */
    public function index(Area $area)
    {
        $jobs = Job::withoutForTenants()->with(
            'area.ancestors',
            'education.education',
            'skills.skillable.ancestors',
            'languages.skillable.ancestors',
            'company'
        )->inArea($area)->finished()->live()->published()->isOpen()->paginate();

        return new JobCollection($jobs);
    }
}
