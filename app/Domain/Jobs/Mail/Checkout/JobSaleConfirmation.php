<?php

namespace Rims\Domain\Jobs\Mail\Checkout;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Rims\Domain\Jobs\Models\JobSale;

class JobSaleConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var JobSale
     */
    public $sale;

    /**
     * Create a new message instance.
     *
     * @param JobSale $sale
     */
    public function __construct(JobSale $sale)
    {
        $this->sale = $sale;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Job payment confirmation')
            ->markdown('tenant.emails.jobs.checkout.sales.confirmation');
    }
}
