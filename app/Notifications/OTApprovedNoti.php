<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OTApprovedNoti extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($myot, $cc_email)
    {
        $this->claim = $myot;
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
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {// standardkan semua link ke email guna yg ni supaya dia 'mark as read'
        $url = route('notify.read', ['nid' => $this->id]);

        // hantar email guna blade template yg berkaitan
        // boleh guna view / markdown
        if($this->claim->verifier_id==null){
            $type='approved';
        }else{
            $type='verified and approved';
        }
        return (new MailMessage)
        ->subject('Overtime claim '.$this->claim->refno.' - Approved')
        ->cc($this->cc_email)
            ->markdown('email.ot.otapproved', [
            'url' => $url,
            'type' => $type,
            'toname' => $this->claim->name->name,
            'claim' => $this->claim->refno
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
            'id' => $this->claim->id,
            'param' => '',
            'route_name' => 'ot.list',
            'text' => 'Your claim ' . $this->claim->refno.' has been approved.',
            'icon' => 'far fa-clock'
          ];
    }
}
