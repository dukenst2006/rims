<?php

namespace Rims\Http\Account\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Account\Requests\UserEducationStoreRequest;
use Rims\Domain\Education\Models\Education;
use Rims\Domain\Users\Models\UserEducation;
use Rims\Domain\Users\Resources\SchoolResource;

class UserEducationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account.education.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserEducationStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserEducationStoreRequest $request)
    {
        $education = Education::find($request->education_id);

        $userEducation = new UserEducation();
        $userEducation->fill($request->only('name', 'course', 'started_at', 'ended_at'));

        $userEducation->user()->associate($request->user());
        $userEducation->education()->associate($education);
        $userEducation->save();

        return response()->json(null, 204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserEducationStoreRequest $request
     * @param UserEducation $userEducation
     * @return \Illuminate\Http\Response
     */
    public function update(UserEducationStoreRequest $request, UserEducation $userEducation)
    {
        $userEducation->fill($request->only('name', 'course', 'started_at', 'ended_at'));
        $userEducation->save();

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param UserEducation $userEducation
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, UserEducation $userEducation)
    {
        $userEducation->delete();

        return response()->json(null, 204);
    }
}
