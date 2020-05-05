<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['id', 'creator_id', 'name', 'description'];

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('authority');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    public function tasks()
    {
        return $this->hasManyThrough('App\Task', 'App\Category');
    }
}
