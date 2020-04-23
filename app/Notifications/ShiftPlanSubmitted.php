<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShiftPlanSubmitted extends Notification
{
    use Queueable;

    protected $shift_group;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sgrp, $theplan)
    {
        //
        $this->shift_group = $sgrp;
        $this->theplan = $theplan;
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
        
        return (new MailMessage)
                    ->subject('Shift Management- Shift Planning (Pending)')
                    ->cc($this->shift_group->Planner->email)
                    ->markdown('email.shift.shiftPlanSubmitted', [
                      'url' => $url,
                      'grpowner_name' => $this->shift_group->Manager->name,
                      'grpname' => $this->shift_group->group_name,
                      'grpplanner_name' => $this->shift_group->Planner->name,
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
        'id' => $this->theplan->id,
        'param' => 'id',
        'route_name' => 'shift.view',
        'text' => 'Shift Planning (Pending) need your approval',
        'icon' => 'fa fa-users'
        ];
    }
}
