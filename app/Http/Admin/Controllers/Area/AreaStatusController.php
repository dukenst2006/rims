<?php

namespace Rims\Http\Admin\Controllers\Area;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Areas\Models\Area;

class AreaStatusController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \Rims\Domain\Areas\Models\Area $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $area)
    {
        $status = $area->usable == true ? false : true;

        $area->update([
            'usable' => $status
        ]);

        $message = $status == true ? 'activated' : 'disabled';

        return back()->withSuccess("{$area->name} is {$message}.");
    }
}
