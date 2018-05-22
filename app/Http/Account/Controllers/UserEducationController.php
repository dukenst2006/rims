<?php

namespace Rims\Http\Account\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Account\Requests\UserEducationStoreRequest;
use Rims\Domain\Education\Models\Education;
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

        $request->user()->schools()->save($education, $request->only('name', 'course', 'started_at', 'ended_at'));

        return response()->json(null, 204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserEducationStoreRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserEducationStoreRequest $request, $id)
    {
        $request->user()->schools()->wherePivot('id', $id)
            ->updateExistingPivot(
                $request->education_id,
                $request->only('name', 'course', 'started_at', 'ended_at')
            );

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
        $request->user()->schools()->wherePivot('id', $id)->detach($request->education_id);

        return response()->json(null, 204);
    }
}
