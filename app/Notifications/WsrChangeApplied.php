<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WsrChangeApplied extends Notification
{
    use Queueable;

    protected $shift_group;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sgrp)
    {
      $this->shift_group = $sgrp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('', ['sgid' => $this->shift_group->id]);
        return (new MailMessage)
                    ->subject('The introduction to the notification.')
                    ->markdown('email.shift.grpcreated', [
                      'url' => $url,
                      'grpname' => $this->shift_group->description
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
          'id' => $this->shift_group->id,
          'class' => 'App\ShiftGroup'
        ];
    }
}
