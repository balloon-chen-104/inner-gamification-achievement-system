<?php

namespace App\Http\Resources\Groups;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupCategory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $categories = [];
        foreach ($this->resource as $category) {
            $categories[] = [
                'id' => $category->id,
                'name' => $category->name
            ];
        }
        return $categories;
        // return [
        //     'id' => $this->id,
        //     'name' => $this->name
        // ];
    }
}
