<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }
}
