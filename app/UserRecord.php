<?php

namespace App;
use App\Shared\URHelper;


use Illuminate\Database\Eloquent\Model;

class UserRecord extends Model
{
    public function statet()
    {
        return $this->belongsTo(State::class,'state_id');
    }
    public function Reg()
    {
        return $this->belongsTo(Psubarea::class,'perssubarea','perssubarea');
    }

    // public function Reg(){//based on OT date
    //   return URHelper::getRegion($this->perssubarea);
    // }

}
