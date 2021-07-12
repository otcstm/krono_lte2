<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GroupMemberAssigned extends Notification
{
    use Queueable;

    protected $shift_group;
    protected $cc_email;
    protected $to_email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sgrp, $cc_email, $to_email)
    {
        //
        $this->shift_group = $sgrp;
        $this->cc_email = $cc_email;
        $this->to_email = $to_email;
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

        $grpplanner_name = '';
        if($this->shift_group->Planner){
            $grpplanner_name = $this->shift_group->Planner->name;  
        }
        
        return (new MailMessage)
                    ->subject('Shift Management - Team Members')
                    ->cc($this->cc_email)
                    ->markdown('email.shift.groupMemberAssigned', [
                      'url' => $url,
                      'grpmember_name' => $this->to_email->name,
                      'grpname' => $this->shift_group->group_name,
                      'grpplanner_name' => $grpplanner_name,
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
        'text' => 'You has been assigned to group ' . $this->shift_group->group_code,
        'icon' => 'fa fa-users'
        ];
    }
}
