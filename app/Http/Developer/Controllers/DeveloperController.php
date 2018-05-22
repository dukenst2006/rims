<?php

namespace Rims\Http\Developer\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;

class DeveloperController extends Controller
{
    /**
     * Display the developer panel view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('developer.index');
    }
}
