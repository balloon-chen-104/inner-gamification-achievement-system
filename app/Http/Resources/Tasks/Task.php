<?php

namespace App\Http\Resources\Tasks;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Tasks\TaskCreator as TaskCreatorResource;
use App\Http\Resources\Tasks\TaskCategory as TaskCategoryResource;

class Task extends JsonResource
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
            'description' => $this->description,
            'score' => $this->score,
            'remain_times' => $this->remain_times,
            'expired_at' => $this->expried_at,
            'creator' => new TaskCreatorResource($this->whenLoaded('creator')),
            'category' => new TaskCategoryResource($this->whenLoaded('category'))
        ];
    }
}
