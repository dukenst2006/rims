<?php

namespace Rims\Http\Tenant\Controllers\Job;

use Exception;
use Rims\Domain\Jobs\Jobs\Checkout\JobSaleCreate;
use Rims\Domain\Jobs\Models\Job;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Stripe\Charge;

class JobCheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Job $job
     * @return \Illuminate\Http\Response
     */
    public function index(Job $job)
    {
        if ($job->isPublished) {
            return back();
        }

        $job->loadMissing('area.ancestors', 'skills.skillable');

        return view('tenant.jobs.checkout.index', compact('job'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Job $job
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Job $job)
    {
        // redirect back if jo is published
        if ($job->isPublished) {
            return back();
        }

        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:
        $token = $request->token;

        try {
            $charge = Charge::create([
                'amount' => $job->cost * 100,    // call function from job model
                'currency' => config('settings.cashier.currency'),
                'description' => 'Job listing payment',
                'source' => $token,
            ]);
        } catch (Exception $exception) {
            $message = $exception->getMessage();

            logger($message, $exception->getTrace());

            return back()->withError("Failed processing payment. Please try again!");
        }

        // record sales
        $request->session()->flash('charge', $charge);

        //dispatch new sale
        dispatch(new JobSaleCreate($job, $request->user()->email, $charge));

        $job->publish();

        return redirect()->route('tenant.jobs.index')
            ->withSuccess("Congratulations. {$job->title} payment successful. Job is now live.");
    }
}
