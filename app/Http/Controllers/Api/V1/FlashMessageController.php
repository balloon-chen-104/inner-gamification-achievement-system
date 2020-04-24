<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bulletin;
use App\Http\Controllers\Api\V1\UpdateApiToken;

class FlashMessageController extends Controller
{
    use UpdateApiToken;

    public function __construct()
    {
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
        if(!isset($request->type) || !isset($request->content)){
            return false;
        } else{
            $type = $request->type;
            $content = trim(htmlentities($request->content));
            $user_id = auth()->user()->id;
            $group_id = auth()->user()->active_group;
            
            if($type != 'flash_message' && $type != 'announcement'){
                return false;
            }

            if($content == ''){
                return false;
            }

            $bulletin = Bulletin::create([
                'type' => $type,
                'content' => $content,
                'user_id' => $user_id,
                'group_id' => $group_id
            ]);

            $this->updateApiToken(auth()->user());
            return $bulletin;
        }
    }
}
