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

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $usersInGroup = Group::find(Auth::user()->active_group)->users;
        $users = [];
        foreach($usersInGroup as $user){
            $tasks = $this->getTasksInfo($user->id, Auth::user()->active_group);
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
        $tasks = $this->getTasksInfo($id, Auth::user()->active_group);
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
        $tasks = $this->getTasksInfo(Auth::user()->id, Auth::user()->active_group);
        $userInfo = User::find(Auth::user()->id);
        
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
                $fileNameToStore = 'user_'.$userInfo->id.'_'.$userInfo->name.'.'.$extension;
                // Upload Image
                $path = $request->file('photo')->storeAs('public/images', $fileNameToStore);
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

    private function getTasksInfo($id, $active_group)
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
            "user_id"     => $id,
            "group_id"    => $active_group
        ]);
        $response = app()->handle($request);
        dd($response);

        // 拿到的 $response 帶有 header
        // 故先轉換型別成字串，去掉 header 再轉換成物件
        // 應急作法，需修正
        $tasks = strval($response);
        $start = 0;
        for($i=0 ; $i<strlen($tasks) ; $i++){
            if($tasks[$i] == '['){
                $start = $i;
                break;
            }
        }
        $tasks = json_decode(substr($tasks, $start));

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
