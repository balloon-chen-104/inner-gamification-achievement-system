<?php

namespace App\Http\Resources\Groups;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupCreator extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'job_title' => $this->job_title,
            'department' => $this->department,
            'office_location' => $this->office_location,
            'extension' => $this->extension
        ];
    }
}
