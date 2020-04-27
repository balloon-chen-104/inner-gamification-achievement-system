<?php

namespace App\Http\Resources\Tasks;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskUser extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->whenPivotLoaded('task_user', function() {
            $confirmed_at = '';
            foreach($this as $user) {
                if($user->id == $this->id){
                    $confirmed_at = $user->pivot->updated_at;
                }
            }
            return $confirmed_at;
        });
    }
}
