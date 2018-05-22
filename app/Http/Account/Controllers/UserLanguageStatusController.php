<?php

namespace Rims\Http\Account\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class UserLanguageStatusController extends Controller
{
    /**
     * Update resource status in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus(Request $request, $id)
    {
        $request->user()->languages()->where('id', $id)->update($request->only('usable'));

        return response()->json(null, 204);
    }
}
