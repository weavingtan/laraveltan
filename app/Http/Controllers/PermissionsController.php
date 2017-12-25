<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Permission::all();
        return view('permissions.index', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        if ($request->wantsJson() && $request->ajax()) {
            return response()->json(Permission::create($request->all()));

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Permission $permission,Request $request)
    {

        $model = $permission;

        return view('permissions.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, Permission $permission )
    {
        if ($request->expectsJson()) {
            $permission->name       = $request->name;
            $permission->guard_name = $request->guard_name;
            return response()->json($permission->save());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Permission $permission,Request $request )
    {
        if($request->expectsJson()){

            return response()->json($permission->delete());
        }
    }

    public function massDestroy(Request $request){

        if ($ids=$request->input('ids')) {
            $entries = Permission::query()->whereIn('id',$ids)->get();
            foreach ($entries as $entry) {
                $entry->delete();
            }
        }

    }
}
