<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Group;
use App\Models\Role;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::paginate(10);

        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = new Group();
        $roles = Role::all();

        return view('groups.create', compact('group', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'Group.name' => ['required', 'unique:groups,name'],
        ]);

        $group = new Group($request->input('Group'));
        $group->save();

        return redirect()->action('GroupsController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Group::findOrFail($id);
        $roles = Role::all();

        return view('groups.edit', compact('group', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'Group.name' => ['required', 'unique:groups,name,' . $id],
        ]);

        $group = Group::findOrFail($id);
        $group->fill($request->input('Group'));
        $group->save();

        $group->roles()->detach();
        $group->roles()->attach($request->input('Role'));

        return redirect()->action('GroupsController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::findOrFail($id);

        $group->delete();

        return redirect()->action('GroupsController@index');
    }
}
