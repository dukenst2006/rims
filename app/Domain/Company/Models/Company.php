<?php

namespace Rims\Domain\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Rims\App\Tenant\Traits\IsTenant;
use Rims\Domain\Jobs\Models\Job;
use Rims\Domain\Jobs\Models\JobApplication;

class Company extends Model
{
    use IsTenant;

    protected $fillable = [
        'name',
        'country',
        'email',
        'phone'
    ];

    /**
     * Get all jobs applications for the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function applications()
    {
        return $this->hasManyThrough(JobApplication::class, Job::class);
    }
}
