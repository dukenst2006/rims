<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Rims\Domain\Jobs\Models\JobSkillable;
use Rims\Domain\Jobs\Models\Job;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Jobs\Requests\JobSkillStoreRequest;
use Rims\Domain\Jobs\Resources\JobSkillCollection;
use Rims\Domain\Jobs\Resources\JobSkillResource;
use Rims\Domain\Skills\Models\Skill;

class JobSkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return JobSkillCollection
     */
    public function index(Job $job)
    {
        $skills = $job->skills()->with('level', 'skillable.ancestors')->get();

        return new JobSkillCollection($skills);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param JobSkillStoreRequest $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return \Illuminate\Http\Response
     */
    public function store(JobSkillStoreRequest $request, Job $job)
    {
        $skill = Skill::find($request->skill_id);

        $exists = $job->hasSkill($skill);

        if ($exists >= 1) {
            return response()->json([
                'message' => 'Skill already exists.'
            ], 403);
        }

        $jobSkillable = new JobSkillable;
        $jobSkillable->fill($request->only('details'));

        $jobSkillable->skillable()->associate($skill);
        $jobSkillable->job()->associate($job);

        $jobSkillable->save();

        return new JobSkillResource($jobSkillable->loadMissing('level', 'skillable.ancestors'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobSkillable $jobSkillable
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job, JobSkillable $jobSkillable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param JobSkillStoreRequest $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobSkillable $jobSkillable
     * @return JobSkillResource
     */
    public function update(JobSkillStoreRequest $request, Job $job, JobSkillable $jobSkillable)
    {
        $skill = Skill::find($request->skill_id);

        $exists = $job->hasSkill($skill);

        if ($exists == 0) {
            $jobSkillable->skillable()->associate($skill);
        }

        $jobSkillable->fill($request->only('details'));
        $jobSkillable->save();

        return new JobSkillResource($jobSkillable->loadMissing('level', 'skillable.ancestors'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobSkillable $jobSkillable
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Job $job, JobSkillable $jobSkillable)
    {
        if ($job->skills->count() == 1) {
            return response()->json([
                'message' => 'Job must have at least one skill.'
            ], 403);
        }

        $jobSkillable->delete();

        return response()->json(null, 204);
    }
}
