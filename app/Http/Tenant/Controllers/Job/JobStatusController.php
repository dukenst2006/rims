<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Jobs\Models\Job;

class JobStatusController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Job $job
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Request $request, Job $job)
    {

        if ($job->education->count() == 0) {
            return response()->json([
                'message' => 'Job must have at least one education requirement.'
            ], 403);
        }

        if ($job->skills->count() == 0) {
            return response()->json([
                'message' => 'Job must have at least one skill.'
            ], 403);
        }

        if($job->requirements->count() == 0) {
            return response()->json([
                'message' => 'Job must have at least one requirement.'
            ], 403);
        }

        if($job->isPublished == false) {
            return response()->json([
                'message' => 'Job must be checked out first to toggle status.'
            ], 403);
        }

        $job->update($request->only('live'));

        return response()->json(null, 204);
    }
}
