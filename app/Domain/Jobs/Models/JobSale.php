<?php

namespace Rims\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Model;
use Rims\App\Tenant\Traits\ForTenants;
use Rims\Domain\Company\Models\Company;

class JobSale extends Model
{
    use ForTenants;

    protected $fillable = [
        'identifier',
        'buyer_email',
        'gateway_id',
        'sale_price',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'identifier';
    }

    /**
     * Gets current month sales.
     *
     * @return mixed
     */
    public static function thisMonthSales()
    {
        $now = Carbon::now();

        return static::whereBetween('created_at', [
            $now->startOfMonth(),
            $now->copy()->endOfMonth(),
        ])->sum('sale_price');
    }

    /**
     * Get lifetime sales.
     *
     * @return mixed
     */
    public static function lifetimeSales()
    {
        return static::get()->sum('sale_price');
    }

    /**
     * Get company that paid for job listing.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get job that sale belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
