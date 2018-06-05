<?php

namespace Rims\Domain\Jobs\Jobs\Checkout;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Rims\Domain\Jobs\Events\JobSaleCreated;
use Rims\Domain\Jobs\Models\Job;
use Rims\Domain\Jobs\Models\JobSale;
use Stripe\Charge;

class JobSaleCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var $email
     */
    public $email;

    /**
     * @var Charge
     */
    public $charge;

    /**
     * @var Job
     */
    public $saleJob;

    /**
     * Create a new job instance.
     *
     * @param Job $saleJob
     * @param $email
     * @param Charge $charge
     */
    public function __construct(Job $saleJob, $email, Charge $charge)
    {
        $this->email = $email;
        $this->charge = $charge;
        $this->saleJob = $saleJob;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //init new sale
        $sale = new JobSale;

        //fill sale details
        $sale->fill([
            'identifier' => uniqid(true),
            'buyer_email' => $this->email,
            'sale_price' => $this->saleJob->cost,
            'gateway_id' => $this->charge->id
        ]);

        //associate sale file and owner
        $sale->job()->associate($this->saleJob);

        //save
        $sale->save();

        //fire sale created event
        event(new JobSaleCreated($sale));
    }
}
