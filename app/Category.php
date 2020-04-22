<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function groups()
    {
        return $this->hasMany('App\Group');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}
