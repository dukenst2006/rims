<?php

namespace Rims\Http\Tenant\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class TenantProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tenant.account.profile.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tenant = $request->tenant();
        $tenant->fill($request->only('name', 'email'));

        //save shop
        $tenant->save();

        return redirect()->route('tenant.account.index')
            ->withSuccess('Company profile updated successfully.');
    }
}
