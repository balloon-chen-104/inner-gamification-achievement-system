<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bulletin;
use App\Group;
use Auth;
use Redirect;

class BulletinController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // New user without any group
        if(!isset(auth()->user()->active_group)){
            return Redirect::to('/');
        }

        $group = Group::find(auth()->user()->active_group);
        // $latestTasks = $group->tasks()->orderBy('updated_at', 'desc')->notExpired()->take(5)->get();
        $latestTasks = $group->tasks()->notExpired()->confirmed()->latest()->take(5)->get();
        $todayDateTime = new \DateTime();
        $todayTimeString = $todayDateTime->format('Y-m-d');

        $flash_messages = Bulletin::where('group_id', Auth::user()->active_group)->where('type', 'flash_message')->where('flash_message_switch', '1')->orderBy('created_at', 'desc')->get();
        $announcements = Bulletin::where('group_id', Auth::user()->active_group)->where('type', 'announcement')->orderBy('updated_at', 'desc')->get();

        // Check if user is admin
        $autority = 0;
        $group_users = Group::find(Auth::user()->active_group)->users;
        foreach($group_users as $group_user){
            if($group_user->pivot->user_id == Auth::user()->id){
                $autority = $group_user->pivot->authority;
            }
        }

        $data = [
            'flash_messages' => $flash_messages,
            'announcements' => $announcements,
            'autority' => $autority,
            'latestTasks' => $latestTasks,
            'todayTimeString' => $todayTimeString
        ];

        return view('bulletin.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // New user without any group
        if(!isset(auth()->user()->active_group)){
            return Redirect::to('/');
        }
        
        // Check if user is admin
        $autority = 0;
        $group_users = Group::find(Auth::user()->active_group)->users;
        foreach($group_users as $group_user){
            if($group_user->pivot->user_id == Auth::user()->id){
                $autority = $group_user->pivot->authority;
            }
        }
        if(!$autority){
            return Redirect::to('/bulletin');
        }

        return view('bulletin.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // New user without any group
        if(!isset(auth()->user()->active_group)){
            return Redirect::to('/');
        }

        // Check if user is admin
        $autority = 0;
        $group_users = Group::find(Auth::user()->active_group)->users;
        foreach($group_users as $group_user){
            if($group_user->pivot->user_id == Auth::user()->id){
                $autority = $group_user->pivot->authority;
            }
        }
        if(!$autority){
            return Redirect::to('/bulletin');
        }
        
        $bulletin = Bulletin::find($id);
        return view('bulletin.edit')->with('bulletin', $bulletin);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $content = trim($request->input('content'));

        if($content == ''){
            return Redirect::to("bulletin/create");
        }

        $bulletin = Bulletin::create([
            'type' => 'announcement',
            'content' => $content,
            'user_id' => Auth::user()->id,
            'group_id' => Auth::user()->active_group
        ]);
        return Redirect::to('bulletin');
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
        $content = trim($request->input('content'));

        if($content == ''){
            return Redirect::to("bulletin/$id/edit");
        }

        $bulletin = Bulletin::find($id);
        $bulletin->content = $content;
        $bulletin->save();
        return Redirect::to("bulletin");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bulletin = Bulletin::find($id);
        $bulletin->delete();
        
        return Redirect::to('bulletin');
    }
}
