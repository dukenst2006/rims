<?php

namespace Rims\Http\Admin\Controllers\User;

use Carbon\Carbon;
use Rims\Domain\Users\Models\Role;
use Rims\Domain\Users\Models\User;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class RoleUsersDisableController extends Controller
{
    /**
     * Revoke role users access in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Rims\Domain\Users\Models\Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function revokeUsersAccess(Request $request, Role $role)
    {
        $this->authorize('delete', $request->user());

        $users = $role->users()
            ->whereNull('expires_at')
            ->orWhereDate('expires_at', '>', Carbon::now())
            ->get();

        $users->each(function ($user, $key) use ($role) {
            $user->roles()->updateExistingPivot($role->id, ['expires_at' => Carbon::now()]);
        });

        return back()->withSuccess("{$role->name} role revoked from assigned users.");
    }
}
