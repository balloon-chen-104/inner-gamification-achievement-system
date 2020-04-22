<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [];

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
