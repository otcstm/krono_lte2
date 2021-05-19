<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShiftGroupCreated extends Notification
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
    // pass related object yg nak guna dalam email / notification
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
    // set kat sini untuk decide jenis notification
    // mail = hantar email. make sure original class yg dinotify tu ada column email
    // database = alert kat menu atas tu
    // pilih je mana yg nak
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
    // standardkan semua link ke email guna yg ni supaya dia 'mark as read'
    $url = route('notify.read', ['nid' => $this->id]);

    // hantar email guna blade template yg berkaitan
    // boleh guna view / markdown
    return (new MailMessage)
      ->subject('Shift Group created under you')
      ->markdown('email.shift.grpcreated', [
        'url' => $url,
        'grpname' => $this->shift_group->group_name
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
        'param' => 'sgid',
        'route_name' => 'shift.mygroup.view',
        'text' => 'You has been assigned as group owner for ' . $this->shift_group->group_code,
        'icon' => 'fa fa-users'
      ];
  }
}
