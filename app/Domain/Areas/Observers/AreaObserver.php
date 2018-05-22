<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 5/7/2018
 * Time: 1:20 AM
 */

namespace Rims\Domain\Areas\Observers;

use Rims\Domain\Areas\Models\Area;

class AreaObserver
{
    /**
     * Listen to resource 'creating' event.
     *
     * @param Area $area
     */
    public function creating(Area $area)
    {
        $prefix = $area->parent ? $area->parent->name . ' ' : '';
        $area->slug = str_slug($prefix . $area->name);

        $area->usable = $area->parent ? true : false;
    }
}