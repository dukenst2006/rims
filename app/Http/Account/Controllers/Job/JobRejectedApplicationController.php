<?php

namespace Rims\Http\Account\Controllers\Job;

use Rims\Domain\Jobs\Models\JobApplication;
use Rims\Domain\Jobs\Models\Job;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class JobRejectedApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $applications = $request->user()->jobApplications()->with('job')
            ->rejected()
            ->orderByDesc('rejected_at')
            ->paginate();

        return view('account.jobs.applications.rejected.index', compact('applications'));
    }
}
