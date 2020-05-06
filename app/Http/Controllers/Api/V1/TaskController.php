<?php

namespace App\Http\Controllers\Api\V1;

use App\Category;
use App\Group;
use App\Http\Controllers\Api\V1\UpdateApiToken;
use App\Http\Resources\Tasks\Task as TaskResource;
use App\Http\Controllers\Controller;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:20',
            'description' => 'required|max:100',
            'category_id' => 'required|integer|min:1',
            'expired_at' => 'required|date_format:Y-m-d|after:tomorrow',
            'score' => 'required|integer|min:1',
            'remain_times' => 'required|integer|min:1',
            'confirmed' => 'required|integer|between:-1,1'
        ]);

        if(Gate::denies('users.viewAny', auth()->user())) {
            return response(['message' => 'The user has no active group.'], 422);
        }

        $category = Category::find($request->input('category_id'));
        if( $category == NULL) {
            return response(['message' => 'The category is not found.'], 422);
        }
        if( $category->group_id != auth()->user()->active_group) {
            return response(['message' => 'This category is not in the current group.'], 422);
        }

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
            'name' => 'required|string|max:20',
            'description' => 'required|string|max:100',
            'category_id' => 'required|integer|min:1',
            'expired_at' => 'required|date_format:Y-m-d|after:tomorrow',
            'score' => 'required|integer|min:1',
            'remain_times' => 'required|integer|min:1',
            'confirmed' => 'required'
        ]);

        if(Gate::denies('users.viewAny', auth()->user())) {
            return response(['message' => 'The user has no active group.'], 422);
        }

        $category = Category::find($request->input('category_id'));
        if( $category == NULL) {
            return response(['message' => 'The category is not found.'], 422);
        }
        if( $category->group_id != auth()->user()->active_group) {
            return response(['message' => 'This category is not in the current group.'], 422);
        }

        $task = $this->task->find($id);
        if($task == NULL) {
            return response(['message' => 'The task is not found.'], 422);
        }
        if(Group::find(auth()->user()->active_group)->tasks()->where('tasks.id', $task->id)->get()->count() == 0) {
            return response(['message' => 'The task is not found in this group.'], 422);
        }

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

    /**
     * @OA\Post(
     *     path="/api/v1/task/report",
     *     tags={"Tasks"},
     *     summary="回報任務",
     *     description="",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="task id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="report",
     *          in="path",
     *          description="report",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="name to store", nullable="false"),
     *             example={"id": 1, "report": "task 1 is done!"}
     *         ),
     *     ),
     *     @OA\Response(response=201, description="successful operation"),
     *     security={
     *         {
     *             "passport": {}
     *         }
     *     }
     * )
     */
    public function report(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'report' => 'nullable'
        ]);

        $task = $this->task->find($request->id);

        if($task != NULL) {
            if($task->users()->where('users.id', auth()->user()->id)->get()->count() == 0){
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
            $task->users()->updateExistingPivot(auth()->user()->id, [
                'confirmed' => 0,
                'report' => $request->report,
                'updated_at' => Carbon::now()
            ]);
            $this->updateApiToken(auth()->user());
            foreach($task->users as $user) {
                if($user->pivot->user_id == auth()->user()->id) {
                    return $user->pivot;
                }
            }
        }
        return response(['message' => 'The task is not found.'], 422);
    }

    public function getConfirmed(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'group_id' => 'required'
        ]);

        if(Gate::denies('users.viewAny', auth()->user())) {
            return response(['message' => 'The user has no active group.'], 422);
        }
        if(User::find($request->input('user_id')) == NULL) {
            return response(['message' => 'The user is not found.'], 422);
        }
        if(Group::find($request->input('group_id')) == NULL) {
            return response(['message' => 'The group is not found.'], 422);
        }

        if(User::find($request->input('user_id'))->groups()->where('groups.id', $request->input('group_id'))->get()->count() == 0){
            return response(['message' => 'The user is not in this group.'], 422);
        }

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
            ->transform(function ($item) {
                return $item = $item->where('id', $item->id)->with('users')->with('category')->first();
            });

        // TaskResource::withoutWrapping();
        return TaskResource::collection($tasks);
    }

    public function approveSuggestion(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'confirmed' => 'required|integer|between:-1,1'
        ]);

        if(Gate::denies('users.viewAny', auth()->user())) {
            return response(['message' => 'The user has no active group.'], 422);
        }
        if(Gate::denies('users.viewAuthority', auth()->user())){
            return response(['message' => 'The user is not authorized.'], 422);
        }

        $task = $this->task->where('id', $request->input('id'))->with('category')->first();
        if($task == NULL) {
            return response(['message' => 'The task is not found.'], 422);
        }

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
            'confirmed' => 'required|integer|between:-1,1'
        ]);

        if(Gate::denies('users.viewAny', auth()->user())) {
            return response(['message' => 'The user has no active group.'], 422);
        }
        if(Gate::denies('users.viewAuthority', auth()->user())){
            return response(['message' => 'The user is not authorized.'], 422);
        }

        $task = $this->task->where('id', $request->input('task_id'))->with('category')->first();
        if($task == NULL) {
            return response(['message' => 'The task is not found.'], 422);
        }
        if($task->users()->where('users.id', $request->input('user_id'))->get()->count() == 0){
            return response(['message' => 'The task is not reported by this user.'], 422);
        }

        if($request->input('confirmed') == 1){
            $task->remain_times--;
        }
        $task->save();
        $task->users()->updateExistingPivot($request->input('user_id'), [
            'confirmed' => $request->input('confirmed'),
            'updated_at' => Carbon::now()
        ]);
        return new TaskResource($task);
    }
}
