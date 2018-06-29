<?php

namespace Rims\Http\Job\Controllers;

use Rims\Domain\Areas\Models\Area;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Jobs\Models\Job;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Area $area
     * @return \Illuminate\Http\Response
     */
    public function index(Area $area)
    {
        return view('jobs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Job $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        // todo: add method to check if job is visible else throw 404

        $job->load([
            'area.ancestors',
            'education.education',
            'skills' => function ($query) {
                $query->with('skill.ancestors')->where('approved', true);
            },
            'categories' => function ($query) {
                $query->with('category.ancestors')->where('approved', true);
            },
            'company'
        ]);

        return view('jobs.show', compact('job'));
    }
}
