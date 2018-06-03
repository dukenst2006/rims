<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Rims\Domain\Jobs\Models\JobSkillable;
use Rims\Domain\Jobs\Models\Job;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Jobs\Requests\JobLanguageStoreRequest;
use Rims\Domain\Jobs\Resources\JobLanguageCollection;
use Rims\Domain\Jobs\Resources\JobLanguageResource;
use Rims\Domain\Languages\Models\Language;
use Rims\Domain\Levels\Models\Level;

class JobLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return JobLanguageCollection
     */
    public function index(Job $job)
    {
        $languages = $job->languages()->with('level', 'skillable.ancestors')->get();

        return new JobLanguageCollection($languages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param JobLanguageStoreRequest $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return \Illuminate\Http\Response
     */
    public function store(JobLanguageStoreRequest $request, Job $job)
    {
        $language = Language::find($request->language_id);
        $level = Level::find($request->level_id);

        $exists = $job->hasLanguage($language);

        if ($exists >= 1) {
            return response()->json([
                'message' => 'Language already exists.'
            ], 403);
        }

        $jobSkillable = new JobSkillable;
        $jobSkillable->fill($request->only('details'));

        $jobSkillable->level()->associate($level);
        $jobSkillable->job()->associate($job);
        $jobSkillable->skillable()->associate($language);

        $jobSkillable->save();

        return new JobLanguageResource($jobSkillable->loadMissing('level', 'skillable.ancestors'));
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
     * @param JobLanguageStoreRequest $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobSkillable $jobSkillable
     * @return JobLanguageResource
     */
    public function update(JobLanguageStoreRequest $request, Job $job, JobSkillable $jobSkillable)
    {
        $language = Language::find($request->language_id);
        $level = Level::find($request->level_id);

        $exists = $job->hasLanguage($language);

        if ($exists == 0) {
            $jobSkillable->skillable()->associate($language);
        }

        $jobSkillable->level()->associate($level);

        $jobSkillable->fill($request->only('details'));
        $jobSkillable->save();

        return new JobLanguageResource($jobSkillable->loadMissing('level', 'skillable.ancestors'));
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
        if ($job->languages->count() == 1) {
            return response()->json([
                'message' => 'Job must have at least one language.'
            ], 403);
        }

        $jobSkillable->delete();

        return response()->json(null, 204);
    }
}
