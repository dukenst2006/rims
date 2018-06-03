<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Rims\Domain\Jobs\Models\JobRequirement;
use Rims\Domain\Jobs\Models\Job;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Jobs\Requests\JobRequirementStoreRequest;
use Rims\Domain\Jobs\Resources\JobRequirementCollection;
use Rims\Domain\Jobs\Resources\JobRequirementResource;

class JobRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job  $job
     * @return JobRequirementCollection
     */
    public function index(Job $job)
    {
        $requirements = $job->requirements;

        return new JobRequirementCollection($requirements);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param JobRequirementStoreRequest $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return JobRequirementResource
     */
    public function store(JobRequirementStoreRequest $request, Job $job)
    {
        $jobRequirement = new JobRequirement;
        $jobRequirement->fill($request->only('title', 'details'));
        $jobRequirement->job()->associate($job);
        $jobRequirement->save();

        return new JobRequirementResource($jobRequirement);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job  $job
     * @param  \Rims\Domain\Jobs\Models\JobRequirement  $jobRequirement
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job, JobRequirement $jobRequirement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param JobRequirementStoreRequest $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobRequirement $jobRequirement
     * @return JobRequirementResource
     */
    public function update(JobRequirementStoreRequest $request, Job $job, JobRequirement $jobRequirement)
    {
        $jobRequirement->fill($request->only('title', 'details'));
        $jobRequirement->save();

        return new JobRequirementResource($jobRequirement);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobRequirement $jobRequirement
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Job $job, JobRequirement $jobRequirement)
    {
        if($job->requirements->count() == 1) {
            return response()->json([
                'message' => 'Job must have at least one requirement.'
            ], 403);
        }

        $jobRequirement->delete();

        return response()->json(null, 204);
    }
}
