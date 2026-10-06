<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes ; 
    protected $fillable = ['user_id','nickname' ,'avatar_path','bio']; 

    public function user ()
    {
        return $this->belongsTo(User::class) ; 
    }
}
