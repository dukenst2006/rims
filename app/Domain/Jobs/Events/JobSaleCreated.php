<?php

namespace Rims\Domain\Jobs\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Rims\Domain\Jobs\Models\JobSale;

class JobSaleCreated
{
    use Dispatchable, SerializesModels;

    /**
     * @var JobSale
     */
    public $sale;

    /**
     * Create a new event instance.
     *
     * @param JobSale $sale
     */
    public function __construct(JobSale $sale)
    {
        $this->sale = $sale;
    }
}
