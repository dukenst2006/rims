<?php

namespace Rims\Http\Education\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Education\Models\Education;
use Rims\Domain\Education\Resources\EducationCollection;

class EducationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return EducationCollection
     */
    public function index()
    {
        $levels = Education::where('usable', true)->get();

        return new EducationCollection($levels);
    }
}
