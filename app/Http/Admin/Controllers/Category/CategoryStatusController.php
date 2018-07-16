<?php

namespace Rims\Http\Admin\Controllers\Category;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Categories\Models\Category;

class CategoryStatusController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \Rims\Domain\Categories\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $status = $category->usable == true ? false : true;

        $category->update([
            'usable' => $status
        ]);

        $message = $status == true ? 'activated' : 'disabled';

        return back()->withSuccess("{$category->name} is {$message}.");
    }
}
