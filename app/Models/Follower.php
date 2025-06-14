<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    protected $table = 'followers';
    
    protected $fillable = ['id', 'user_id', 'follower_id','created_at','updated_at'];
}
