<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Rims\Domain\Areas\Models\Area;
use Rims\Domain\Jobs\Models\Job;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Jobs\Requests\JobStoreRequest;
use Rims\Domain\Jobs\Resources\JobResource;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tenant.jobs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JobResource
     */
    public function create()
    {
        $job = Job::create([
            'title' => 'Untitled',
            'overview_short' => 'None',
            'overview' => 'None',
            'applicants' => 1,
            'type' => 'full-time',
            'on_location' => true,
            'salary_min' => 0.00,
            'salary_max' => 0.00,
            'live' => false,
            'approved' => false,
            'finished' => false,
        ]);

        return new JobResource($job);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param JobStoreRequest $request
     * @param Job $job
     * @return JobResource
     */
    public function store(JobStoreRequest $request, Job $job)
    {
        $area = Area::find($request->area_id);

        //save
        $job->fill(
            $request->only([
                'title', 'overview_short', 'overview',
                'applicants', 'type', 'on_location',
                'salary_min', 'salary_max'
            ])
        );
        $job->slug = SlugService::createSlug(Job::class, 'slug', $job->title);
        $job->finished = true;

        // area
        $job->area()->associate($area);

        $job->save();

        return new JobResource($job->loadMissing('area.ancestors'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param JobStoreRequest $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return JobResource
     */
    public function update(JobStoreRequest $request, Job $job)
    {
        $area = Area::find($request->area_id);

        //save
        $job->fill(
            $request->only([
                'title', 'overview_short', 'overview',
                'applicants', 'type', 'on_location',
                'salary_min', 'salary_max'
            ])
        );
        $job->finished = true;

        // area
        $job->area()->associate($area);

        $job->save();

        return new JobResource($job->loadMissing('area.ancestors'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Job $job)
    {
        $job->delete();

        return response()->json(null, 204);
    }
}
