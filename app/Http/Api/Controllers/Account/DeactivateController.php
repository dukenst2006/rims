<?php

namespace Rims\Http\Api\Controllers\Account;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Account\Requests\DeactivateAccountRequest;

class DeactivateController extends Controller
{

    /**
     * Handle account deactivation.
     *
     * @param DeactivateAccountRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeactivateAccountRequest $request)
    {
        $user = $request->user();

        if ($user->subscribed('main')) {
            $user->subscription('main')->cancel();
        }

        $user->delete();

        return response()->json(null, 204);
    }
}
