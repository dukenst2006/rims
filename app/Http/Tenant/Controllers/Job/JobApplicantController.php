<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Rims\Domain\Jobs\Models\JobApplication;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class JobApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $applications = $request->tenant()->applications()->submitted()->isNotCancelled()->with('user', 'job')->paginate();

        return view('tenant.jobs.applicants.index', compact('applications'));
    }
}
