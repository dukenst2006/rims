<?php

namespace Rims\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Model;
use Rims\App\Traits\HasApprovals;
use Rims\Domain\Categories\Models\Category;

class JobCategory extends Model
{
    use HasApprovals;

    protected $fillable = [
        'approved',
        'category_id'
    ];

    /**
     * Get category that owns this job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get job that category belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
