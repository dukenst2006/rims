<?php

namespace Rims\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Model;
use Rims\Domain\Education\Models\Education;

class JobEducation extends Model
{
    protected $table = 'job_education';

    protected $fillable = [
        'details'
    ];

    /**
     * Get education level that owns this requirement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function education()
    {
        return $this->belongsTo(Education::class);
    }

    /**
     * Get job that owns this requirement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
