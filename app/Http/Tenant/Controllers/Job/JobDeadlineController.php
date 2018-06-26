<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Jobs\Models\Job;
use Rims\Domain\Jobs\Requests\JobDeadlineStoreRequest;
use Rims\Domain\Jobs\Resources\JobResource;

class JobDeadlineController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param JobDeadlineStoreRequest $request
     * @param Job $job
     * @return JobResource
     */
    public function store(JobDeadlineStoreRequest $request, Job $job)
    {
        if ($job->published && !$job->isOpenForRestore) {
            return response()->json([
                'message' => 'Sorry, you can no longer re-open this job since it is past the maximum allowed time.'
            ], 403);
        }

        $date = Carbon::parse($request->closed_at);

        $job->update([
            'closed_at' => $date
        ]);

        return new JobResource($job);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Job $job
     * @return JobResource
     */
    public function restore(Request $request, Job $job)
    {
        if ($job->published && !$job->isOpenForRestore) {
            return response()->json([
                'message' => 'Sorry, you can no longer re-open this job since it is past the maximum allowed time.'
            ], 403);
        }

        $job->update([
            'closed_at' => null
        ]);

        return new JobResource($job);
    }
}
