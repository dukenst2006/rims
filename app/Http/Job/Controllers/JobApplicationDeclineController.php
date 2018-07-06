<?php

namespace Rims\Http\Job\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Jobs\Models\Job;
use Rims\Domain\Jobs\Models\JobApplication;

class JobApplicationDeclineController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Job $job, JobApplication $jobApplication)
    {
        $jobApplication->declined_at = Carbon::now();
        $jobApplication->save();

        return redirect()->route('jobs.applications.show', [$job, $jobApplication])
            ->withSuccess('Your decline message has been sent to job listing owner.');
    }
}
