<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Rims\Domain\Jobs\Models\JobApplication;
use Rims\Domain\Jobs\Models\Job;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return \Illuminate\Http\Response
     */
    public function index(Job $job)
    {
        $applications = $job->applications()->submitted()->isNotCancelled()
            ->with('user')
            ->orderByDesc('submitted_at')->paginate();

        return view('tenant.jobs.applications.index', compact('job', 'applications'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job, JobApplication $jobApplication)
    {
        $jobApplication->load('user', 'user.skills.skill', 'user.schools.education');

        return view('tenant.jobs.applications.show', compact('job', 'jobApplication'));
    }
}
