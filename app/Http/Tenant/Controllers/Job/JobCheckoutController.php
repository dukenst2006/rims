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
        if (!$job->isReadyForCheckout) {
            return back();
        }

        $job->loadMissing('area.ancestors', 'categories.category.ancestors', 'skills.skill.ancestors');

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
        if (!$job->IsReadyForCheckout) {
            return back();
        }

        // default message
        $message = "{$job->title} payment successful. Job is now live.";

        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:
        $token = $request->token;

        if ($job->cost > 0) {

            try {
                $charge = Charge::create([
                    'amount' => $job->gatewayCost('stripe'),    // call function from job model
                    'currency' => config('settings.cashier.currency'),
                    'description' => 'Job listing payment',
                    'source' => $token,
                ]);
            } catch (Exception $exception) {
                $message = $exception->getMessage();

                logger($message, $exception->getTrace());

                return back()->withError("Failed processing payment. Please try again!");
            }

            //dispatch new sale
            dispatch(new JobSaleCreate($job, $request->user()->email, $charge));

        }

        // free and published message
        if ($job->isPublished) {
            $message = "Changes applied to '{$job->title}' successfully.";
        }

        // paid and published message
        if ($job->cost > 0 && $job->isPublished) {
            $message = "'{$job->title}' payment successful. All changes applied.";
        }

        // publish job
        $job->publish();

        return redirect()->route('tenant.jobs.index')
            ->withSuccess("Congratulations. {$message}");
    }
}
