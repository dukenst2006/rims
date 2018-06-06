<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Rims\Domain\Jobs\Models\JobSkill;
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
        $skills = $job->skills()->with('level', 'skill.ancestors')->get();

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

        $jobSkill = new JobSkill;
        $jobSkill->fill($request->only('details'));

        $jobSkill->skill()->associate($skill);
        $jobSkill->job()->associate($job);

        $jobSkill->save();

        return new JobSkillResource($jobSkill->loadMissing('level', 'skill.ancestors'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobSkill $jobSkillable
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job, JobSkill $jobSkillable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param JobSkillStoreRequest $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobSkill $jobSkill
     * @return JobSkillResource
     */
    public function update(JobSkillStoreRequest $request, Job $job, JobSkill $jobSkill)
    {
        $skill = Skill::find($request->skill_id);

        $exists = $job->hasSkill($skill);

        if ($exists == 0) {
            $jobSkill->skill()->associate($skill);
        }

        $jobSkill->fill($request->only('details'));
        $jobSkill->save();

        return new JobSkillResource($jobSkill->loadMissing('level', 'skill.ancestors'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobSkill $jobSkill
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Job $job, JobSkill $jobSkill)
    {
        if ($job->skills->count() == 1) {
            return response()->json([
                'message' => 'Job must have at least one skill.'
            ], 403);
        }

        $jobSkill->delete();

        return response()->json(null, 204);
    }
}
