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
     * @OA\Post(
     *     path="/api/v1/flashMessage",
     *     tags={"Flash Message"},
     *     summary="新增",
     *     description="新增一則新的快訊，type 只接受 flash_message 否則回傳 false",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="name to store", nullable="false"),
     *             example={"type": "flash_message", "content": "最新快訊！！！"}
     *         ),
     *     ),
     *     @OA\Response(response=201, description="新增快訊成功"),
     *         security={
     *             {"bearerAuth": {"api_key": "UTY7IplIoZsvrxrXr4hehyYBi1KRF0ZaHkoHCMScvnvslaotkDOTxMIYCledD63p82lfpa6RNJmVpivP"}}
     *     }
     * )
     */
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
