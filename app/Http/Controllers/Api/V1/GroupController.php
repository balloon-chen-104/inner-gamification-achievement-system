<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Group;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\Groups\Group as GroupResource;
use App\Http\Controllers\Api\V1\UpdateApiToken;
use Illuminate\Support\Str;

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
        $groupInDB = Group::where('name', $request->input('name'))->first();
        if($groupInDB == NULL){
            $this->group->name = $request->input('name');
            $this->group->creator_id = auth()->user()->id;
            $this->group->description = $request->input('description');
            $this->group->group_token = Str::random(5);
            $this->group->save();
            $this->group->users()->attach(auth()->user()->id, ['authority' => 1]);
            $this->updateApiToken($this->group->creator);

            return new GroupResource($this->group);
        }
        return response(['message' => '群組：'. $request->input('name'). '已存在'], 422);
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

    public function enter(Request $request)
    {
        $request->validate([
            'group_token' => 'required|size:5'
        ]);
        $token = $request->input('group_token');
        if(Group::where('group_token', $token)->first() != NULL) {
            $enter_group = $this->group->with('users')->where('group_token', $token)->first();

            if($enter_group->users()->where('users.id', auth()->user()->id)->first() == NULL) {
                $enter_group->users()->attach(auth()->user()->id, ['authority' => 0]);
                $this->updateApiToken(auth()->user());

                return new GroupResource($enter_group);
            }
            return response([ "message" => "你已在此群組" ], 422);


        }
        return response([ "message" => "這個 group ID 找不到符合的群組" ], 422);
    }
}
