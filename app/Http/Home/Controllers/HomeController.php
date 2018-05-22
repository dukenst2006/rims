<?php

namespace Rims\Http\Home\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.index');
    }
}
