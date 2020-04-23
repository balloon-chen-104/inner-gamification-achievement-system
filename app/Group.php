<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function categories()
    {
        return $this->hasMany('App\Category');
    }
}
