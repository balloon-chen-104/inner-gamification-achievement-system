<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Group;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\Groups\Group as GroupResource;
use App\Http\Controllers\Api\V1\UpdateApiToken;

class GroupController extends Controller
{
    use UpdateApiToken;

    protected $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
        $this->middleware('auth:api')->only(['store', 'update', 'destroy', 'enter']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return GroupResource::collection(Group::with('creator')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:10',
            'description' => 'required|max:100'
        ]);

        $this->group->name = $request->input('name');
        $this->group->creator_id = auth()->user()->id;
        $this->group->description = $request->input('description');
        $this->group->save();
        $this->group->users()->attach(auth()->user()->id, ['authority' => 1]);
        $this->updateApiToken($this->group->creator);

        return new GroupResource($this->group);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new GroupResource(Group::where('id', $id)->with('categories')->first());
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function enter(Request $request)
    {
        $request->validate([
            'group_token' => 'required|size:5'
        ]);
        $token = $request->input('group_token');
        $enter_group = $this->group->with('users')->where('group_token', $token)->first();
        $enter_group->users()->attach(auth()->user()->id, ['authority' => 0]);

        $this->updateApiToken(auth()->user());

        return new GroupResource($enter_group);
    }
}
