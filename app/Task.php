<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [];
    protected $hidden = ['category_id', 'creator_id'];

    public function scopeNotExpired(Builder $query)
    {
        return $query->where('expired_at', '>', Carbon::now())->orderBy('expired_at');
    }

    public function scopeExpired(Builder $query)
    {
        return $query->where('expired_at', '<', Carbon::now())->orderBy('expired_at', 'desc');
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

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
