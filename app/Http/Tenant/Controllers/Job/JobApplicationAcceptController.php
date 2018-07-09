<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Carbon\Carbon;
use Rims\Domain\Jobs\Models\JobApplication;
use Rims\Domain\Jobs\Models\Job;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class JobApplicationAcceptController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Job $job, JobApplication $jobApplication)
    {
        $jobApplication->accepted_at = Carbon::now();
        $jobApplication->save();

        // todo: send email notification

        return back()->withSuccess('Applicant will be notified their application is accepted.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job, JobApplication $jobApplication)
    {
        $jobApplication->accepted_at = null;
        $jobApplication->rejected_at = Carbon::now();
        $jobApplication->save();

        // todo: send email notification

        return back()->withSuccess('Applicant will be notified their application has been rejected.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job, JobApplication $jobApplication)
    {
        $jobApplication->accepted_at = null;
        $jobApplication->save();

        // todo: send email notification

        return back()->withSuccess('Applicant will be notified their application has been reconsidered.');
    }
}
