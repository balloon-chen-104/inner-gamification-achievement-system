<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\UpdateApiToken;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\Categories\Category as CategoryResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use UpdateApiToken;

    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
        $this->middleware('auth:api')->only(['store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryResource::collection($this->category->with('group')->get());
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
            'name' => 'required|max:15',
        ]);
        if(auth()->user()->active_group != NULL) {
            $this->category->name = $request->input('name');
            $this->category->group_id = auth()->user()->active_group;
            $this->category->save();
            $this->updateApiToken(auth()->user());
            return new CategoryResource($this->category);
        }
        return response([ "message" => "This user has no active group." ], 422);
    }
}
