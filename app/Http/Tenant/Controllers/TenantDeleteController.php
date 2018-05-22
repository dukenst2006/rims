<?php

namespace Rims\Http\Tenant\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class TenantDeleteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tenant.account.delete.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->company->delete();

        return redirect()->route('account.dashboard')
            ->withSuccess('Company deleted.');
    }
}
