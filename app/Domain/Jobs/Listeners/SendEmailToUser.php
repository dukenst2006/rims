<?php

namespace Rims\Domain\Jobs\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Rims\Domain\Jobs\Events\JobSaleCreated;
use Rims\Domain\Jobs\Mail\Checkout\JobSaleConfirmation;

class SendEmailToUser
{
    /**
     * Handle the event.
     *
     * @param JobSaleCreated $event
     * @return void
     */
    public function handle(JobSaleCreated $event)
    {
        Mail::to($event->sale->buyer_email)->send(new JobSaleConfirmation($event->sale));
    }
}
