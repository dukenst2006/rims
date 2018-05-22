<?php

namespace Rims\Http\Account\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Languages\Models\Language;
use Rims\Domain\Levels\Models\Level;
use Rims\Domain\Users\Models\UserLanguage;

class UserLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account.languages.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $level = Level::find($request->level_id);
        $language = Language::find($request->language_id);

        $userLanguage = new UserLanguage();
        $userLanguage->level()->associate($level);
        $userLanguage->language()->associate($language);
        $userLanguage->user()->associate($request->user());
        $userLanguage->save();

        return response()->json(null, 204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $level = Level::find($request->level_id);

        $userLanguage = $request->user()->languages()->where('id', $id)->firstOrFail();
        $userLanguage->level()->associate($level);
        $userLanguage->save();

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->user()->languages()->where('id', $id)->delete();

        return response()->json(null, 204);
    }
}
