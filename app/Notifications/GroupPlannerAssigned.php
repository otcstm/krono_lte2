<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GroupPlannerAssigned extends Notification
{
    use Queueable;

    protected $shift_group;
    protected $cc_email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sgrp, $cc_email)
    {
        //
        $this->shift_group = $sgrp;
        $this->cc_email = $cc_email;
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
                    ->subject('Shift Management- Shift Planner')
                    ->cc($this->cc_email)
                    ->markdown('email.shift.groupPlannerAssigned', [
                      'url' => $url,
                      'grpname' => $this->shift_group->group_name,
                      'grpplanner_name' => $this->shift_group->Planner->name,
                      'btn_val' => 'View Shift Planner Page',
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
            //
        'id' => $this->shift_group->id,
        'param' => 'sgid',
        'route_name' => 'shift.mygroup.view',
        'text' => 'You has been assigned as group planner for ' . $this->shift_group->group_code,
        'icon' => 'fa fa-users'
        ];
    }
}
