<?php

namespace Rims\Http\Tenant\Controllers;

use Rims\Domain\Company\Events\CompanyUserLogin;
use Rims\Domain\Company\Models\Company;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class TenantSwitchController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function switch(Company $company)
    {
        event(new CompanyUserLogin(
            request()->user(),
            $company
        ));

        if (session()->has('success')) {
            return redirect()->route('tenant.dashboard')
                ->withSuccess(session('success'));
        }

        return redirect()->route('tenant.dashboard');
    }
}
