<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 5/11/2018
 * Time: 1:40 PM
 */

namespace Rims\Domain\Education\Observers;

use Rims\Domain\Education\Models\Education;

class EducationObserver
{
    /**
     * Listen to resource 'creating' event.
     *
     * @param Education $education
     */
    public function creating(Education $education)
    {
        $education->slug = str_slug($education->name);
        $education->usable = true;
    }
}