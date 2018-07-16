<?php

namespace Rims\Http\Admin\Controllers\Area;

use Rims\Domain\Areas\Models\Area;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Areas\Requests\AreaStoreRequest;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = Area::with('parent')->get()->toFlatTree();

        return view('admin.areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $areas = Area::with('parent')->get()->toFlatTree();

        return view('admin.areas.create', compact('areas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AreaStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AreaStoreRequest $request)
    {
        $area = new Area;
        $area->fill($request->only('name'));
        $area->parent_id = $request->parent_id;
        $area->save();

        return redirect()->route('admin.areas.index')->withSuccess("{$area->name} successfully added.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Areas\Models\Area $area
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Rims\Domain\Areas\Models\Area $area
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area)
    {
        $areas = Area::with('parent')->get()->toFlatTree();

        return view('admin.areas.edit', compact('areas', 'area'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AreaStoreRequest $request
     * @param  \Rims\Domain\Areas\Models\Area $area
     * @return \Illuminate\Http\Response
     */
    public function update(AreaStoreRequest $request, Area $area)
    {
        $area->fill($request->only('name', 'usable'));
        $area->parent_id = $request->parent_id;
        $area->save();

        return back()->withSuccess('Area successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Areas\Models\Area $area
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Area $area)
    {
        try {
            $area->delete();
        } catch (\Exception $e) {
            return back()->withError("Failed deleting `{$area->name}` from areas.");
        }

        return back()->withSuccess("`{$area->name}` successfully deleted from areas.");
    }
}
