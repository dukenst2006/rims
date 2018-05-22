<?php

namespace Rims\Http\Account\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Languages\Models\Language;
use Rims\Domain\Languages\Resources\LanguageCollection;
use Rims\Domain\Levels\Models\Level;
use Rims\Domain\Levels\Resources\LevelCollection;
use Rims\Domain\Users\Resources\UserLanguageCollection;

class UserLanguageIndexController extends Controller
{
    public function index(Request $request)
    {
        $languages = $request->user()->languages;

        return new UserLanguageCollection($languages);
    }

    public function languages()
    {
        $languages = Language::get()->toTree();

        return new LanguageCollection($languages);
    }

    public function levels()
    {
        $levels = Level::where('usable', true)->get();

        return new LevelCollection($levels);
    }
}
