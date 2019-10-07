<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $fillable = [

        'activity_type', 'module_name' ,'user_id' ,'ip_address' ,'user_agent' , 'session_id'

    ];
    
    public function getUserTbl(){
	    return $this->belongsTo('App\User', 'user_id');
	}
}
