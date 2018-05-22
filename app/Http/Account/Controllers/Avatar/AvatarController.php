<?php

namespace Rims\Http\Account\Controllers\Avatar;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class AvatarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account.avatar.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->user()->update($request->only('avatar_id'));

        return back()->withSuccess('Avatar changed successfully.');
    }
}
