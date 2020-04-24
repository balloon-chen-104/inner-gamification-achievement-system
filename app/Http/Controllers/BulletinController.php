<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bulletin;
use App\Group;
use Auth;
use Redirect;

class BulletinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flash_messages = Bulletin::where('group_id', Auth::user()->active_group)->where('type', 'flash_message')->where('flash_message_switch', '1')->orderBy('created_at', 'desc')->get();
        $announcements = Bulletin::where('group_id', Auth::user()->active_group)->where('type', 'announcement')->orderBy('updated_at', 'desc')->get();

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
            'autority' => $autority
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
        $content = $request->input('content');
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
        $content = $request->input('content');
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
