<?php

namespace Rims\Http\Language\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Languages\Models\Language;
use Rims\Domain\Languages\Resources\LanguageCollection;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return LanguageCollection
     */
    public function index()
    {
        $languages = Language::get()->toTree();

        return new LanguageCollection($languages);
    }
}
