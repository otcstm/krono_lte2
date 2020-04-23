<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OTQueryApprove extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($myot)
    {
        $this->claim = $myot;
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
        // standardkan semua link ke email guna yg ni supaya dia 'mark as read'
        $url = route('notify.read', ['nid' => $this->id]);
        $query = "";
        foreach($this->claim->log as $logs){
            if(strpos($logs->message,"Queried")!==false){
                $query = $logs->message; 
            }
        }
        $reason = str_replace('"', '', str_replace('Queried with message: "', '', $query));
        // hantar email guna blade template yg berkaitan
        // boleh guna view / markdown
        return (new MailMessage)
        ->subject('Overtime claim '.$this->claim->refno.' - Queried')
        ->markdown('email.ot.otquery', [
            'url' => $url,
            'reason' => $reason,
            'toname' => $this->claim->name->name,
            'doer' => $this->claim->approver->name,
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
            'text' => 'Overtime claim ' . $this->claim->refno.' - Queried',
            'icon' => 'far fa-clock'
          ];
    }
}
