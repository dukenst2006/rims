<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 5/11/2018
 * Time: 4:16 PM
 */

namespace Rims\Domain\Languages\Observers;

use Rims\Domain\Languages\Models\Language;

class LanguageObserver
{
    /**
     * Listen to resource 'creating' event.
     *
     * @param Language $language
     */
    public function creating(Language $language)
    {
        $prefix = $language->parent ? $language->parent->name . ' ' : '';
        $language->slug = str_slug($prefix . $language->name);

        $language->usable = $language->parent ? true : false;
    }
}