<?php

namespace App\Http\Controllers\Api\V1;

use App\Group;
use App\Http\Controllers\Api\V1\UpdateApiToken;
use App\Http\Resources\Tasks\Task as TaskResource;
use App\Http\Controllers\Controller;
use App\Task;
use Carbon\Carbon;
use GuzzleHttp\Promise\TaskQueue;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use UpdateApiToken;
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'description' => 'required',
            'category_id' => 'required',
            'expired_at' => 'required|date_format:Y-m-d',
            'score' => 'required',
            'remain_times' => 'required'
        ]);
        $this->task->name = $request->input('name');
        $this->task->description = $request->input('description');
        $this->task->category_id = $request->input('category_id');
        $this->task->creator_id = auth()->user()->id;
        $this->task->confirmed = 1;
        $this->task->expired_at = Carbon::parse($request->input('expired_at'));
        $this->task->score = $request->input('score');
        $this->task->remain_times = $request->input('remain_times');
        $this->task->save();

        $this->updateApiToken(auth()->user());

        $id = $this->task->id;
        return new TaskResource($this->task->where('id', $id)->with('category')->first());
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
            'remain_times' => 'required'
        ]);
        $task = $this->task->find($id);

        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->category_id = $request->input('category_id');
        $task->expired_at = Carbon::parse($request->input('expired_at'));
        $task->score = $request->input('score');
        $task->remain_times = $request->input('remain_times');
        $task->save();

        $this->updateApiToken(auth()->user());

        return new TaskResource($task->where('id', $id)->with('category')->first());
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

    public function getConfirmedTasks()
    {
        if(isset(auth()->user()->active_group)) {
            $tasks = Group::find(auth()->user()->active_group)->tasks
                ->filter(function($value, $key) {
                    if($value->users->count() > 0){
                        foreach($value->users as $user) {
                            if($user->id == auth()->user()->id && $user->pivot->confirmed == 1) {
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

    public function approveSuggestionTask(Request $request)
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

    public function verifyTask(Request $request)
    {
        $request->validate([
            'id' => 'required',
            // 'confirmed' => 'required'
        ]);
        $task = $this->task->where('id', $request->input('id'))->with('category')->first();
        $task->remain_times--;
        $task->save();
        $task->users()->updateExistingPivot(auth()->user()->id, [
            'confirmed' => 1,
            'updated_at' => Carbon::now()
        ]);
        return new TaskResource($task);
    }
}
