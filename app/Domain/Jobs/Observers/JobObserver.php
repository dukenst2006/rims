<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 5/25/2018
 * Time: 3:40 PM
 */

namespace Rims\Domain\Jobs\Observers;

use Illuminate\Database\Eloquent\Model;
use Rims\App\Tenant\Observers\TenantObserver;
use Rims\Domain\Areas\Models\Area;

class JobObserver extends TenantObserver
{
    /**
     * Listen to given tenant model creating event.
     *
     * @param Model $model
     */
    public function creating(Model $model)
    {
        if (!isset($model->area_id)) {
            $model->area()->associate(Area::first()->id);
        }

        if (!isset($model->identifier)) {
            $model->setAttribute('identifier', uniqid(true));
        }

        parent::creating($model);
    }
}