<?php

namespace App;

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
}
