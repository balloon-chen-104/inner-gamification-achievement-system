<?php

namespace App\Http\Resources\Groups;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Groups\GroupCreator as GroupCreatorResource;
use App\Http\Resources\Groups\GroupUser as GroupUserResource;
use App\Http\Resources\Groups\GroupCategory as GroupCategoryResource;

class Group extends JsonResource
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
            'creator' => new GroupCreatorResource($this->whenLoaded('creator')),
            'users' => new GroupUserResource($this->whenLoaded('users')),
            'categories' => new GroupCategoryResource($this->whenLoaded('categories')),
        ];
    }
}
