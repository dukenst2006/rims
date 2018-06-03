<?php

namespace Rims\Http\Level\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Levels\Models\Level;
use Rims\Domain\Levels\Resources\LevelCollection;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return LevelCollection
     */
    public function index()
    {
        $levels = Level::where('usable', true)->get();

        return new LevelCollection($levels);
    }
}
