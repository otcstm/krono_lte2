<?php

namespace app\Api;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;


use App\Shared\GeoLocHelper;
use App\Shared\LeaveHelper;
use Illuminate\Http\Request;

use App\Notifications\ShiftGroupCreated;

class DemoController extends BaseController
{
    public function location($lat, $lon)
    {
        $loc = null;


        $loc = GeoLocHelper::getLocDescr($lat, $lon);
    
        return $loc;
    }
    

    public function userLeave($user_id, $dt)
    {
        $cl = LeaveHelper::getLeave($user_id, $dt);
        return $cl;
    }

    public function sendnotify(Request $req)
    {
        /*
1. buat dulu notification object
$   php artisan make:notification ShiftGroupCreated

2. declare jenis notification dalam function 'via' kat object notification tu.
tengok contoh dalam App\Notifications\ShiftGroupCreated

3. kalau email, buat sekali view / markdown untuk email content
copy paste je contoh view/markdown kat dalam resources/views/email/*

4. untuk notification kat menu atas tu, make sure dalam notification object tu at least ada array ni:
public function toArray($notifiable)
{
          return [
            'id' => $this->shift_group->id,   // ni untuk parameter ke page tu
            'param' => 'sgid',                // ni untuk parameter argument ke page tu. blank kalau tak perlu
            'route_name' => 'shift.mygroup.view', // nama route untuk redirect
            'text' => 'You has been assigned as group owner for ' . $this->shift_group->group_code, // text yg akan tunjuk kat menu atas
            'icon' => 'fa fa-users'           // icon. kalau perlu
          ];
}

4.1 kalau perlu customize redirect link, ubah kat app\Shared\AlertHelper::getUrl

5. pastu, kat tempat2 yg akan trigger event tu, panggil cam contoh kat bawah ni


         */


        // user yang akan terima notification tu
        $user = $req->user();

        // object yang nak dinotify / tengok bila penerima notify tekan link
        $mygrp = \App\ShiftGroup::where('manager_id', $user->id)->first();

        // hantar notification ke user tu, untuk action yang berkaitan
        $user->notify(new ShiftGroupCreated($mygrp));
    }
}
