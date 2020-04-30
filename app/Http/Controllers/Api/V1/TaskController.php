<?php

namespace App\Http\Controllers\Api\V1;

use App\Group;
use App\Http\Controllers\Api\V1\UpdateApiToken;
use App\Http\Resources\Tasks\Task as TaskResource;
use App\Http\Controllers\Controller;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use UpdateApiToken;
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->middleware('auth:api')->except('getConfirmedTasks');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'expired_at' => 'required|date_format:Y-m-d',
            'score' => 'required',
            'remain_times' => 'required',
            'confirmed' => 'required'
        ]);
        $this->task->name = $request->input('name');
        $this->task->description = $request->input('description');
        $this->task->category_id = $request->input('category_id');
        $this->task->creator_id = auth()->user()->id;
        $this->task->confirmed = $request->input('confirmed');
        $this->task->expired_at = Carbon::parse($request->input('expired_at'));
        $this->task->score = $request->input('score');
        $this->task->remain_times = $request->input('remain_times');
        $this->task->save();

        $this->updateApiToken(auth()->user());

        $id = $this->task->id;
        return new TaskResource($this->task->where('id', $id)->with('category')->first());
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
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'expired_at' => 'required|date_format:Y-m-d',
            'score' => 'required',
            'remain_times' => 'required',
            'confirmed' => 'required'
        ]);
        $task = $this->task->find($id);

        $newConfirmed = 1;
        if($request->input('confirmed') == -1) {
            $newConfirmed = 0;
        }
        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->category_id = $request->input('category_id');
        $task->expired_at = Carbon::parse($request->input('expired_at'));
        $task->score = $request->input('score');
        $task->remain_times = $request->input('remain_times');
        $task->confirmed = $newConfirmed;
        $task->save();

        $this->updateApiToken(auth()->user());

        return new TaskResource($task->where('id', $id)->with('category')->first());
    }

    public function report(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'report' => 'required'
        ]);
        $task = $this->task->find($request->id);
        $task->users()->attach(auth()->user()->id, [
            'confirmed' => 0,
            'report' => $request->report,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $this->updateApiToken(auth()->user());
        foreach($task->users as $user) {
            if($user->pivot->user_id == auth()->user()->id) {
                return $user->pivot;
            }
        }
    }

    public function getConfirmed(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'group_id' => 'required'
        ]);
        if($request->input('group_id')!== NULL) {
            $tasks = Group::find($request->input('group_id'))->tasks
                ->filter(function($value) use($request) {
                    if($value->users->count() > 0){
                        foreach($value->users as $user) {
                            if($user->id == $request->input('user_id') && $user->pivot->confirmed == 1) {
                                return true;
                            }
                        }
                    }
                    return false;
                })
                ->transform(function ($item, $key) {
                    return $item = $item->where('id', $item->id)->with('users')->with('category')->first();
                });
        }else {
            return ['message' => 'This user has no active group.'];
        }

        TaskResource::withoutWrapping();
        return TaskResource::collection($tasks);
    }

    public function approveSuggestion(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'confirmed' => 'required'
        ]);
        $task = $this->task->where('id', $request->input('id'))->with('category')->first();
        $task->confirmed = $request->input('confirmed');
        $task->save();
        $this->updateApiToken(auth()->user());
        return new TaskResource($task);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'task_id' => 'required',
            'user_id' => 'required',
            'confirmed' => 'required'
        ]);
        $task = $this->task->where('id', $request->input('task_id'))->with('category')->first();
        $task->remain_times--;
        $task->save();
        $task->users()->updateExistingPivot($request->input('user_id'), [
            'confirmed' => $request->input('confirmed'),
            'updated_at' => Carbon::now()
        ]);
        return new TaskResource($task);
    }
}
