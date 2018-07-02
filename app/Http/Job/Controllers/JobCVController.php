<?php

namespace Rims\Http\Job\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Rims\Domain\Jobs\Models\JobApplication;
use Rims\Domain\Jobs\Models\Job;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Jobs\Requests\JobCVStoreRequest;

class JobCVController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param JobCVStoreRequest $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function store(JobCVStoreRequest $request, Job $job, JobApplication $jobApplication)
    {
        // catch uploaded file
        $uploadedFile = $request->file('file');

        // get uploaded file name
        $uploadName = $this->generateFilename($uploadedFile);

        // set up path
        $path = "jobs/{$job->identifier}/applications/{$jobApplication->identifier}/cv";

        // store file in local storage
        Storage::disk('local')->putFileAs(
            $path,
            $uploadedFile,
            $uploadName
        );

        // save in storage
        $jobApplication->update([
            'cv' => $uploadName
        ]);

        return response()->json([
            'data' => [
                'name' => $jobApplication->cv,
                'path' => $jobApplication->cvPath
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job, JobApplication $jobApplication)
    {
        return Storage::disk('local')->download($jobApplication->getCVFullPath());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job, JobApplication $jobApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Jobs\Models\Job $job
     * @param  \Rims\Domain\Jobs\Models\JobApplication $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job, JobApplication $jobApplication)
    {
        //
    }

    /**
     * Get's original filename from client.
     *
     * @param UploadedFile $uploadedFile
     * @return null|string
     */
    protected function generateFilename(UploadedFile $uploadedFile)
    {
        return $uploadedFile->getClientOriginalName();
    }
}
