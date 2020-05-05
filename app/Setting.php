<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['id', 'cycle', 'started_at', 'group_id'];
}
