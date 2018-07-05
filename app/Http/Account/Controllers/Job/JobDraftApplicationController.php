<?php

namespace Rims\Http\Account\Controllers\Job;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class JobDraftApplicationController extends Controller
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
            ->drafted()
            ->orderByDesc('submitted_at')
            ->paginate();

        return view('account.jobs.applications.drafts.index', compact('applications'));
    }
}
