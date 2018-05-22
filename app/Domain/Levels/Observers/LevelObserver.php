<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 5/11/2018
 * Time: 2:14 PM
 */

namespace Rims\Domain\Levels\Observers;

use Rims\Domain\Levels\Models\Level;

class LevelObserver
{
    /**
     * Listen to resource 'creating' event.
     *
     * @param Level $level
     */
    public function creating(Level $level)
    {
        $level->slug = str_slug($level->name);
        $level->usable = true;
    }
}