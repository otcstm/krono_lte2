<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShiftPlanMembersApproved extends Notification
{
    use Queueable;

    protected $shift_group;
    protected $theplan;
    protected $asps;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sgrp, $theplan, $asps)
    {
        //
        $this->shift_group = $sgrp;
        $this->theplan = $theplan;
        $this->asps = $asps;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // standardkan semua link ke email guna yg ni supaya dia 'mark as read'
        $url = route('notify.read', ['nid' => $this->id]);
        $cc_user = [];
        array_push($cc_user, $this->shift_group->Manager->email);
        array_push($cc_user, $this->shift_group->Planner->email);


        return (new MailMessage)
                    ->subject('Shift Management- Shift Planning (Approved)')
                    ->cc($cc_user)
                    ->markdown('email.shift.shiftPlanMembersApproved', [
                      'url' => $url,
                      'grpmember_name' => $this->asps->User->name,
                      'grpname' => $this->shift_group->group_name,
                      'btn_val' => 'View My Shift',
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
        'id' => 'myc',
        'param' => 'page',
        'route_name' => 'staff.worksched',
        'text' => 'Shift Planning for you has been approved',
        'icon' => 'glyphicon glyphicon-ok-sign text-green'
        ];
    }
}
