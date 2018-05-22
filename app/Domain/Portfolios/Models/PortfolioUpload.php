<?php

namespace Rims\Domain\Portfolios\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Rims\App\Traits\HasApprovals;

class PortfolioUpload extends Model
{
    use SoftDeletes,
        HasApprovals;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'fullPath'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename',
        'path',
        'type',
        'size',
        'approved',
        'preview',
    ];

    /**
     * Get the full file url.
     *
     * @param $value
     * @return string
     */
    public function getPathAttribute($value)
    {
        if ($this->uploadable()->getModel()->getTable() === 'users') {
            return Storage::url("users/{$this->uploadable->id}/{$this->portfolio->identifier}/{$value}");
        }

        return $value;
    }

    /**
     * Get the full file upload path.
     *
     * @return string
     */
    public function getFullPathAttribute()
    {
        if ($this->uploadable()->getModel()->getTable() === 'users') {
            return storage_path("users/{$this->uploadable->id}/{$this->portfolio->identifier}/{$this->path}");
        }

        return null;
    }

    /**
     * Get the upload's owning model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function uploadable()
    {
        return $this->morphTo();
    }

    /**
     * Get portfolio that own's the upload.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
