<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Rims\Domain\Education\Models\Education;
use Rims\Domain\Jobs\Models\JobEducation;
use Rims\Domain\Jobs\Models\Job;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Jobs\Requests\JobEducationStoreRequest;
use Rims\Domain\Jobs\Resources\JobEducationCollection;
use Rims\Domain\Jobs\Resources\JobEducationResource;

class JobEducationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return JobEducationCollection
     */
    public function index(Job $job)
    {
        $education = $job->education()->with('education')->get();

        return new JobEducationCollection($education);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param JobEducationStoreRequest $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return JobEducationResource
     */
    public function store(JobEducationStoreRequest $request, Job $job)
    {
        $education = Education::find($request->education_id);

        $jobEducation = new JobEducation;
        $jobEducation->fill($request->only('details'));
        $jobEducation->job()->associate($job);
        $jobEducation->education()->associate($education);
        $jobEducation->save();

        return new JobEducationResource($jobEducation->loadMissing('education'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobEducation $jobEducation
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job, JobEducation $jobEducation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobEducation $jobEducation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job, JobEducation $jobEducation)
    {
        $education = Education::find($request->education_id);

        $jobEducation->fill($request->only('details'));
        $jobEducation->education()->associate($education);
        $jobEducation->save();

        return new JobEducationResource($jobEducation->loadMissing('education'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobEducation $jobEducation
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Job $job, JobEducation $jobEducation)
    {
        if ($job->education->count() == 1) {
            return response()->json([
                'message' => 'Job must have at least one education requirement.'
            ], 403);
        }

        $jobEducation->delete();

        return response()->json(null, 204);
    }
}
