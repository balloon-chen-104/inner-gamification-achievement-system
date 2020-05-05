<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'password', 'api_token', 'department', 'job_title', 'self_expectation', 'active_group'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tasks()
    {
        return $this->belongsToMany('App\Task')->withPivot(['confirmed', 'report', 'updated_at'])->withTimestamps();
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group')->withPivot('authority');
    }

    public function createdGroups()
    {
        return $this->hasMany('App\Group', 'creator_id');
    }

    public function createdTasks()
    {
        return $this->hasMany('App\Task', 'creator_id');
    }
}
