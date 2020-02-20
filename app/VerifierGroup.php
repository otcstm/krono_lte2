<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerifierGroup extends Model
{
    //    
    public function Members(){
      return $this->hasMany(VerifierGroupMember::class, 'user_verifier_groups_id');
    }
  
    public function Approver(){
      return $this->belongsTo(User::class, 'approver_id');
    }
  
    public function Verifier(){
      return $this->belongsTo(User::class, 'verifier_id');
    }
}
