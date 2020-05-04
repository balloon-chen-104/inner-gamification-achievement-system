<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [];
    protected $hidden = [];

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
        return $query->orderBy('tasks.'.static::CREATED_AT, 'desc');
    }

    public function scopeToday(Builder $query)
    {
        return $query->where('tasks.'. static::CREATED_AT, '>', Carbon::today());
    }

    public function scopeRemain(Builder $query)
    {
        return $query->where('remain_times', '>', 0);
    }

    public function scopeNoRemain(Builder $query)
    {
        return $query->where('remain_times', '=', 0);
    }

    public function scopeConfirmed(Builder $query)
    {
        return $query->where('confirmed', 1);
    }
    public function scopeNotConfirmed(Builder $query)
    {
        return $query->where('confirmed', 0);
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot(['confirmed', 'report', 'created_at', 'updated_at']);
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
