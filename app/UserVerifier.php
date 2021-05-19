<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVerifier extends Model
{
    public function tblUser_userid()
    {
        return $this->belongsTo(User::class,'id');
    }

    public function tblUser_verifierid()
    {
        return $this->belongsTo(User::class,'id');
    }

    public function tblUserRecord_userid()
    { 
        return $this->hasOne(UserRecord::class)->latest();
    }
}
