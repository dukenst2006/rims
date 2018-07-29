<?php

namespace Rims\Http\Admin\Controllers\User;

use Rims\App\Controllers\Controller;
use Rims\Domain\Users\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('create', $request->user());

        $users = User::filter($request)->paginate();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', $request->user());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', $request->user());
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Users\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $this->authorize('view', $request->user());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Rims\Domain\Users\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        $this->authorize('update', $request->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Rims\Domain\Users\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $request->user());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  \Rims\Domain\Users\Models\User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $request->user());

        try {
            $user->delete();
        } catch (\Exception $e) {
            return back()->withError("Failed deleting {$user->name} account.");
        }

        return back()->withSuccess("{$user->name} account deleted.");
    }
}
