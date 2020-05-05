<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = ['id', 'group_id', 'name'];

    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}
