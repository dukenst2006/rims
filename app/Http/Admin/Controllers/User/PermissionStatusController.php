<?php

namespace Rims\Http\Admin\Controllers\User;

use Rims\Domain\Users\Models\Permission;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class PermissionStatusController extends Controller
{
    /**
     * Update the specified resource status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Rims\Domain\Users\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Request $request, Permission $permission)
    {
        $permission->fill([
            'usable' => $permission->usable == true ? false : true
        ]);

        $permission->save();

        return back()->withSuccess("{$permission->name} status updated successfully.");
    }
}
