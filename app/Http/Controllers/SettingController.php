<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use App\Bulletin;
use Auth;
use Redirect;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // First time enter setting page without data
        $setting = Setting::where('group_id', Auth::user()->active_group)->get();
        if(!isset($setting[0])){
            $today = date('Y-m-d');
            $setting = Setting::create(['cycle' => 30, 'started_at' => $today, 'group_id' => Auth::user()->active_group]);
        }
        
        $setting = Setting::where('group_id', Auth::user()->active_group)->get();
        
        $bulletin = Bulletin::where('group_id', Auth::user()->active_group)->where('type', 'flash_message')->orderBy('created_at', 'desc')->get();

        // If expired
        $expiredDate = date_add(date_create($setting[0]->started_at), date_interval_create_from_date_string($setting[0]->cycle . 'days'));
        $expiredDateTimestamp = $expiredDate->getTimestamp();
        $todayTimestamp = new \DateTime();
        $todayTimestamp = $todayTimestamp->getTimestamp();
        if($expiredDateTimestamp < $todayTimestamp){
            // Call calculate API

            // Refresh started_at
            $setting[0]->started_at = date("Y-m-d");
            $setting[0]->save();
        }


        $data = [
            'cycle' => $setting[0]->cycle,
            'started_at' => $setting[0]->started_at,
            'cycle_error' => '',
            'flash_messages' => $bulletin
        ];
        return view('setting.index')->with('data', $data);
    }

    public function editCycle()
    {
        $setting = Setting::where('group_id', Auth::user()->active_group)->get();
        $bulletin = Bulletin::where('group_id', Auth::user()->active_group)->where('type', 'flash_message')->orderBy('created_at', 'desc')->get();
        $data = [
            'cycle' => $setting[0]->cycle,
            'started_at' => $setting[0]->started_at,
            'cycle_error' => '',
            'flash_messages' => $bulletin
        ];
        return view('setting.editCycle')->with('data', $data);
    }

    public function updateCycle(Request $request)
    {
        $setting = Setting::where('group_id', Auth::user()->active_group)->get();
        $bulletin = Bulletin::where('group_id', Auth::user()->active_group)->where('type', 'flash_message')->get();
        $cycle = $request->cycle;
        if(!is_numeric($cycle)){
            $data = [
                'cycle' => $setting[0]->cycle,
                'started_at' => $setting[0]->started_at,
                'cycle_error' => '請輸入數字',
                'flash_messages' => $bulletin
            ];
            return view('setting.editCycle')->with('data', $data);
        } else{
            $expiredDate = date_add(date_create($setting[0]->started_at), date_interval_create_from_date_string($cycle . 'days'));
            $expiredDateTimestamp = $expiredDate->getTimestamp();
            $todayTimestamp = new \DateTime();
            $todayTimestamp = $todayTimestamp->getTimestamp();
            if($expiredDateTimestamp < $todayTimestamp){
                $data = [
                    'cycle' => $setting[0]->cycle,
                    'started_at' => $setting[0]->started_at,
                    'cycle_error' => '結算日早於今天日期，請設定更長的週期',
                    'flash_messages' => $bulletin
                ];
                return view('setting.editCycle')->with('data', $data);
            } else{
                $setting = Setting::where('group_id', Auth::user()->active_group)->get();
                $setting[0]->cycle = $cycle;
                $setting[0]->save();

                return Redirect::to("/setting");
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editFlashMessage($id)
    {
        $setting = Setting::where('group_id', Auth::user()->active_group)->get();
        $bulletin = Bulletin::where('group_id', Auth::user()->active_group)->where('type', 'flash_message')->orderBy('created_at', 'desc')->get();
        $data = [
            'cycle' => $setting[0]->cycle,
            'started_at' => $setting[0]->started_at,
            'cycle_error' => '',
            'flash_messages' => $bulletin,
            'id' => $id
        ];
        return view('setting.editFlashMessage')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateFlashMessage(Request $request, $id)
    {
        if($request->flash_message_switch){
            $bulletin = Bulletin::find($id);
            $bulletin->flash_message_switch = ($bulletin->flash_message_switch == 1) ? 0 : 1;
            $bulletin->save();
            if($request->flash_message_switch == 'bulletin'){
                return Redirect::to("bulletin");
            } else{
                return Redirect::to("setting");
            }
        } else{
            $bulletin = Bulletin::find($id);

            $flashMessage = trim($request->flashMessage);
            if($flashMessage == ''){
                return Redirect::to("setting/$id/editFlashMessage");
            }

            $bulletin->content = $flashMessage;
            $bulletin->save();
            return Redirect::to("setting");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyFlashMessage($id)
    {
        $bulletin = Bulletin::find($id);
        $bulletin->delete();

        return Redirect::to('setting');
    }

    public function createFlashMessage()
    {
        return view('setting.createFlashMessage');
    }
}
