<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    protected $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
        $this->middleware('auth');
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
            'name' => 'required',
            'description' => 'required'
        ]);
        $user = auth()->user();
        $this->group->creator_id = $user->id;
        $this->group->name = $request->input('name');
        $this->group->description = $request->input('description');
        $this->group->group_token = Str::random(5);
        $this->group->save();
        $this->group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $this->group->id;
        $user->save();

        return Redirect::to("bulletin");
    }

    public function enter(Request $request)
    {
        $request->validate([
            'group-id' => 'required'
        ]);
        $group = $this->group->where('group_token', $request->input('group-id'))->first();
        $user = auth()->user();
        // dd($user);
        $group->users()->attach($user->id, ['authority' => 0]);

        $user->active_group = $group->id;
        $user->save();

        return Redirect::to("bulletin");
    }
}
