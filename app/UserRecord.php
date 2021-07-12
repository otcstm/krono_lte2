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

    public function companyid()
    {
        return $this->belongsTo(Company::class,'company_id')->withDefault(['company_descr' => 'N/A']);
    }
    public function Reg()
    {
        return $this->belongsTo(Psubarea::class,'perssubarea','perssubarea');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getreg(){
         return URHelper::getRegion($this->perssubarea);
       }


}
