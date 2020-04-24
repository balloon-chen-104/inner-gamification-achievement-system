<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    protected $fillable = ['type', 'content', 'user_id', 'group_id'];
}
