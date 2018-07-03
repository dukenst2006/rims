<?php

namespace Rims\Http\Job\Controllers;

use Carbon\Carbon;
use Rims\Domain\Jobs\Models\JobApplication;
use Rims\Domain\Jobs\Models\Job;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Jobs\Requests\JobApplicationStoreRequest;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Job $job)
    {
        $jobApplication = new JobApplication;
        $jobApplication->cv = 'Undefined';
        $jobApplication->details = 'Undefined';
        $jobApplication->finished = false;
        $jobApplication->job()->associate($job);
        $jobApplication->user()->associate($request->user());
        $jobApplication->save();

        return redirect()->route('jobs.applications.create', [$job, $jobApplication]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Job $job, JobApplication $jobApplication)
    {
        if ($jobApplication->submitted) {
            return redirect()->route('jobs.applications.show', [$job, $jobApplication]);
        }

        if ($jobApplication->finished) {
            return redirect()->route('jobs.applications.edit', [$job, $jobApplication]);
        }

        return view('jobs.applications.create', compact('job', 'jobApplication'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param JobApplicationStoreRequest $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function store(JobApplicationStoreRequest $request, Job $job, JobApplication $jobApplication)
    {
        $jobApplication->fill($request->only('cv', 'details'));
        $jobApplication->finished = true;
        $jobApplication->save();

        return redirect()->route('jobs.applications.edit', [$job, $jobApplication]);
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

        return view('jobs.applications.show', compact('job', 'jobApplication'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job, JobApplication $jobApplication)
    {
        if ($jobApplication->submitted) {
            return redirect()->route('jobs.applications.show', [$job, $jobApplication]);
        }

        if (!$jobApplication->finished) {
            return back();
        }

        return view('jobs.applications.edit', compact('job', 'jobApplication'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param JobApplicationStoreRequest $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function update(JobApplicationStoreRequest $request, Job $job, JobApplication $jobApplication)
    {
        $jobApplication->fill($request->only('cv', 'details'));
        $jobApplication->save();

        if ($request->apply) {
            $jobApplication->update([
                'submitted_at' => Carbon::now()
            ]);

            return redirect()->route('jobs.applications.show', [$job, $jobApplication])
                ->withSuccess("Congratulations! Your application has been submitted.")
                ->withInfo("Remember to login or check your email to get application notifications.");
        }

        return back()->withSuccess("Application details updated successfully. Send application when you are ready.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Job $job, JobApplication $jobApplication)
    {
        $jobApplication->delete();

        return back()->withSuccess("{$job->title} application has been deleted successfully.");
    }
}
