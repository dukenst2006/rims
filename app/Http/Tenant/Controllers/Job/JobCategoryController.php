<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Rims\Domain\Jobs\Models\JobCategory;
use Rims\Domain\Jobs\Models\Job;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Jobs\Requests\JobCategoryStoreRequest;
use Rims\Domain\Jobs\Resources\JobCategoryCollection;

class JobCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return JobCategoryCollection
     */
    public function index(Job $job)
    {
        $categories = $job->categories()->with('category')->without('job')->get();

        return new JobCategoryCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param JobCategoryStoreRequest $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @return JobCategoryCollection
     */
    public function store(JobCategoryStoreRequest $request, Job $job)
    {
        $categories = collect($request->category_id);

        $jobCategories = $categories->map(function ($category, $key) use ($job) {
            return [
                'job_id' => $job->id,
                'category_id' => $category,
                'approved' => false
            ];
        })->all();

        // create categories
        $job->categories()->createMany($jobCategories);

        return new JobCategoryCollection($job->categories->load('category'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobCategory $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job, JobCategory $jobCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobCategory $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job, JobCategory $jobCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobCategory $jobCategory
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Job $job, JobCategory $jobCategory)
    {
        if($jobCategory->approved) {
            return response()->json([
                'message' => 'Category cannot be deleted.'
            ], 403);
        }

        $jobCategory->delete();

        return response()->json(null, 204);
    }
}
