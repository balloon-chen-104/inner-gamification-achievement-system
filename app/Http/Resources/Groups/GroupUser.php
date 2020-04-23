<?php

namespace App\Http\Resources\Groups;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupUser extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $users = [];
        foreach ($this->resource as $user) {
            $users[] = [
                'id' => $user->id,
                'name' => $user->name,
                'job_title' => $user->job_title,
                'department' => $user->department,
                'office_location' => $user->office_location,
                'extension' => $user->extension
            ];
        }
        return $users;
        // return [
        //     'id' => $this->id,
        //     'name' => $this->name,
        //     'job_title' => $this->job_title,
        //     'department' => $this->department,
        //     'office_location' => $this->office_location,
        //     'extension' => $this->extension
        // ];
    }
}
