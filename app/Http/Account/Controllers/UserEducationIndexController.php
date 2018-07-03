<?php

namespace Rims\Http\Account\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Education\Models\Education;
use Rims\Domain\Users\Resources\SchoolCollection;

class UserEducationIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return SchoolCollection
     */
    public function index(Request $request)
    {
        $schools = $request->user()->schools()->with('education')
            ->orderBy('started_at', 'asc')
            ->orderBy('ended_at', 'asc')
            ->get();

        return new SchoolCollection($schools);
    }

    public function levels()
    {
        $levels = Education::where('usable', true)->get();

        return response()->json([
            'data' => $levels
        ], 200);
    }
}
