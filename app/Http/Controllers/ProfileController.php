<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Setting;
use App\Group;
use Auth;
use Redirect;

class ProfileController extends Controller
{
    // define scores of rank
    private $medals = [
        50 => '銅牌III',
        100 => '銅牌II',
        150 => '銅牌I',
        250 => '銀牌III',
        350 => '銀牌II',
        450 => '銀牌I',
        600 => '金牌III',
        750 => '金牌II'
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // New user without any group
        if(!isset(auth()->user()->active_group)){
            return Redirect::to('/');
        }

        $usersInGroup = Group::find(Auth::user()->active_group)->users;
        $users = [];
        foreach($usersInGroup as $user){
            $tasks = $this->getTasksInfo($user->id);
            $users[] = [
                'id' => $user->id,
                'name' => $user->name,
                'api_token' => $user->api_token,
                'photo' => $user->photo,
                'medal' => $tasks['medal'],
                'periodScore' => $tasks['periodScore'],
                'allScore' => $tasks['allScore']
            ];
        }

        // DESC sort by periodScore
        usort($users, function($a, $b) {
            return $b['periodScore'] <=> $a['periodScore'];
        });
        
        return view('leaderboard.index')->with('users', $users);
    }

    public function show($id)
    {
        $url = url()->current();
        $i = strpos($url, 'profile/');
        $userId = substr($url, $i);
        $i = strpos($userId, '/');
        $userId = substr($userId, $i+1);
        
        // New user without any group
        if(!isset(auth()->user()->active_group)){
            if($userId != Auth::user()->id){
                return Redirect::to('/profile/'.Auth::user()->id);
            }

            $userInfo = User::find($id);

            $data = [
                'id' => $userInfo->id,
                'name' => $userInfo->name,
                'photo' => $userInfo->photo,
                'email' => $userInfo->email,
                'self_expectation' => $userInfo->self_expectation,
                'job_title' => $userInfo->job_title,
                'department' => $userInfo->department,
                'office_location' => $userInfo->office_location,
                'extension' => $userInfo->extension,
                'photo' => $userInfo->photo
            ];
            return view('profile.show')->with('data', $data);
        }
        
        // check if user id in this group
        $users = Group::find(auth()->user()->active_group)->users;
        $users_id = [];
        foreach($users as $user){
            $users_id[] = $user->id;
        }
        if(!in_array($userId, $users_id)){
            return Redirect::to('/profile/'.Auth::user()->id);
        }

        $userInfo = User::find($id);
        $tasks = $this->getTasksInfo($userInfo->id);

        $data = [
            'id' => $userInfo->id,
            'name' => $userInfo->name,
            'photo' => $userInfo->photo,
            'email' => $userInfo->email,
            'self_expectation' => $userInfo->self_expectation,
            'job_title' => $userInfo->job_title,
            'department' => $userInfo->department,
            'office_location' => $userInfo->office_location,
            'extension' => $userInfo->extension,
            'photo' => $userInfo->photo,
            'periodScore' => $tasks['periodScore'],
            'allScore' => $tasks['allScore'],
            'completeTasksInThisPeriod' => $tasks['completeTasksInThisPeriod'],
            'completeTasksInThePast' => $tasks['completeTasksInThePast'],
            'medals' => [
                'medal' => $tasks['medal'],
                'scoreToNextRank' => $tasks['scoreToNextRank'],
                'scoreToNextRankRemain' => $tasks['scoreToNextRankRemain'],
                'currentScoreInThisRank' => $tasks['currentScoreInThisRank']
            ]
        ];
        return view('profile.show')->with('data', $data);
    }

    public function edit()
    {
        // Check if user is in his/her own profile
        $url = url()->current();
        $i = strpos($url, 'profile/');
        $id = substr($url, $i);
        $i = strpos($id, '/');
        $id = substr($id, $i+1);
        $i = strpos($id, '/');
        $id = substr($id, 0, $i);
        if($id != Auth::user()->id){
            return Redirect::to('/profile/'.$id);
        }

        // New user without any group
        if(!isset(auth()->user()->active_group)){
            $userInfo = User::find($id);

            $data = [
                'id' => $userInfo->id,
                'name' => $userInfo->name,
                'photo' => $userInfo->photo,
                'email' => $userInfo->email,
                'self_expectation' => $userInfo->self_expectation,
                'job_title' => $userInfo->job_title,
                'department' => $userInfo->department,
                'office_location' => $userInfo->office_location,
                'extension' => $userInfo->extension,
                'photo' => $userInfo->photo
            ];
            return view('profile.edit')->with('data', $data);
        }
        
        
        $userInfo = User::find(Auth::user()->id);
        $tasks = $this->getTasksInfo(Auth::user()->id);

        $data = [
            'id' => $userInfo->id,
            'name' => $userInfo->name,
            'photo' => $userInfo->photo,
            'email' => $userInfo->email,
            'self_expectation' => $userInfo->self_expectation,
            'job_title' => $userInfo->job_title,
            'department' => $userInfo->department,
            'office_location' => $userInfo->office_location,
            'extension' => $userInfo->extension,
            'photo' => $userInfo->photo,
            'periodScore' => $tasks['periodScore'],
            'allScore' => $tasks['allScore'],
            'completeTasksInThisPeriod' => $tasks['completeTasksInThisPeriod'],
            'completeTasksInThePast' => $tasks['completeTasksInThePast'],
            'medals' => [
                'medal' => $tasks['medal'],
                'scoreToNextRank' => $tasks['scoreToNextRank'],
                'scoreToNextRankRemain' => $tasks['scoreToNextRankRemain'],
                'currentScoreInThisRank' => $tasks['currentScoreInThisRank']
            ]
        ];
        return view('profile.edit')->with('data', $data);
    }

    public function update(Request $request, $id)
    {
        $userInfo = User::find(Auth::user()->id);

        if(isset($request->photo)){
            $this->validate($request, [
                'photo' => 'image|nullable|max:1999'
            ]);

            // Handle File Upload
            if($request->hasFile('photo')){
                // Get just extension
                $extension = $request->file('photo')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = 'user_'.$userInfo->id.'_'.time().'.'.$extension;
                // Upload Image
                $path = $request->file('photo')->storeAs('public/images/'.'user_'.$userInfo->id, $fileNameToStore);
            } else {
                $fileNameToStore = 'default-photo.jpg';
            }
            $userInfo->photo = $fileNameToStore;
        }


        $self_expectation = trim($request->self_expectation);
        if($self_expectation == ''){
            return Redirect::to("profile/$id/edit");
        }

        $userInfo->self_expectation = $self_expectation;
        $userInfo->save();
        return Redirect::to("profile/$id");
    }

    private function getTasksInfo($id)
    {
        $periodScore = 0;
        $allScore = 0;
        $completeTasksInThisPeriod = [];
        $completeTasksInThePast = [];
        $medal = '';
        $scoreToNextRank = 0;
        $scoreToNextRankRemain = 0;
        $currentScoreInThisRank = 0;


        $request = Request::create('api/v1/task/confirmed', 'POST', [
            'user_id' => $id,
            'group_id' => Auth::user()->active_group
        ]);
        // $request->headers->set(
        //     // 'Authorization', 'Bearer '.Auth::user()->api_token
        //     'Authorization', 'Bearer '.$api_token
        // );
        $response = app()->handle($request);

        $tasks = $response->getData();


        // Enter leaderboard or profile without default setting
        // (Same in SettingController: First time enter setting page without data)
        $setting = Setting::where('group_id', Auth::user()->active_group)->get();
        if(!isset($setting[0])){
            $today = date('Y-m-d');
            $setting = Setting::create(['cycle' => 30, 'started_at' => $today, 'group_id' => Auth::user()->active_group]);
        }

        $startedDate = Setting::where('group_id', auth()->user()->active_group)->first()->started_at;
        $startedDate = date_create($startedDate);
        $startedDateTimestamp = $startedDate->getTimestamp();

        if(count($tasks) != 0){
            foreach($tasks as $task){
                $task->confirmed_at = date_create($task->confirmed_at);
                $task->confirmed_at_timestamp = $task->confirmed_at->getTimestamp();
                if($task->confirmed_at_timestamp >= $startedDateTimestamp){
                    $periodScore += $task->score;
                    $completeTasksInThisPeriod[] = '完成'.$task->name.'獲得'.$task->score.'分';
                } else{
                    $completeTasksInThePast[] = '完成'.$task->name.'獲得'.$task->score.'分';
                }
                $allScore += $task->score;
            }
            $preScore = 0;
            foreach($this->medals as $score => $rank){
                if($allScore < $score){
                    $medal = $rank;
                    $scoreToNextRankRemain = $score - $allScore;
                    $scoreToNextRank = $score - $preScore;
                    $currentScoreInThisRank = $allScore - $preScore;
                    break;
                }
                $preScore = $score;
            }
            $medal = ($medal == '') ? '金牌I' : $medal;
            $currentScoreInThisRank = ($currentScoreInThisRank == 0) ? 1 : $currentScoreInThisRank;
            $scoreToNextRank = ($scoreToNextRank == 0) ? 1 : $scoreToNextRank;
        } else{
            $medal = '銅牌III';
            $medals = array_flip($this->medals);
            $score = array_shift($medals);
            $scoreToNextRank = $score;
            $scoreToNextRankRemain = $score;
        }

        return [
            'periodScore' => $periodScore,
            'allScore' => $allScore,
            'completeTasksInThisPeriod' => $completeTasksInThisPeriod,
            'completeTasksInThePast' => $completeTasksInThePast,
            'medal' => $medal,
            'scoreToNextRank' => $scoreToNextRank,
            'scoreToNextRankRemain' => $scoreToNextRankRemain,
            'currentScoreInThisRank' => $currentScoreInThisRank,
        ];
    }
}
