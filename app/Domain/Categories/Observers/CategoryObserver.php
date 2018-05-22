<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 5/7/2018
 * Time: 1:18 AM
 */

namespace Rims\Domain\Categories\Observers;

use Rims\Domain\Categories\Models\Category;

class CategoryObserver
{
    /**
     * Listen to resource 'creating' event.
     *
     * @param Category $category
     */
    public function creating(Category $category)
    {
        $prefix = $category->parent ? $category->parent->name . ' ' : '';
        $category->slug = str_slug($prefix . $category->name);

        $category->usable = $category->parent ? true : false;
    }
}