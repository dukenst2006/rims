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
     * Get the full file upload path.
     *
     * @return string
     */
    public function getFullPathAttribute()
    {
        $uploadPath = "{$this->uploadable->id}/portfolios/{$this->portfolio->identifier}/{$this->filename}";

        if ($this->uploadable()->getModel()->getTable() === 'users') {
            return Storage::url("users/{$uploadPath}");
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
