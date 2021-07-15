<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OTSubmitted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($myot, $cc_email,$tover,$toapp,$touser)
    {
      // dd($myot, $cc_email,$tover,$toapp,$touser);
        $this->claim = $myot;
        $this->cc_email = $cc_email;
        $this->toapp = $toapp;
        $this->touser = $touser;
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
            // $bccmail = 'otneo2020@gmail.com';

            if($this->claim->verifier_id==NULL){
              // dd('here1');
                $subject = "Approval";
                $type = "approval";
                $toname = $this->claim->approver->name;

                if ($this->touser!='') {
                    // dd('cc user',$this->toapp,$this->touser);
                    $ccmail = $this->touser;
                }else{
                  // dd('user invalid',$this->toapp,$this->touser);
                  $ccmail = 'ot@tm.com.my';
                }

            }else{
                // dd('here2');
              $subject = "Verification";
              $type = "verification";
              $toname = $this->claim->verifier->name;

              if(($this->toapp!='')&&($this->touser!='')){
                // dd('valid both',$this->toapp,$this->touser);
                $ccmail = $this->cc_email;
              }elseif ($this->toapp!='') {
                // dd('cc app',$this->toapp,$this->touser);
              $ccmail = $this->toapp;
              }elseif ($this->touser!='') {
                // dd('cc user',$this->toapp,$this->touser);
                $ccmail = $this->touser;
              }else {
                // dd('invalid both',$this->toapp,$this->touser);
                $ccmail = 'ot@tm.com.my';
              }

            }
            // hantar email guna blade template yg berkaitan
            // boleh guna view / markdown


            return (new MailMessage)
            ->subject('Overtime claim '.$this->claim->refno.' - Pending '.$subject)
            ->cc($ccmail)
            ->markdown('email.ot.otsubmittedverified', [
                'url' => $url,
                'type' => $type,
                'appname' => $this->claim->name->name,
                'toname' => $toname,
                'extra' => '',
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
        $route = "ot.verify";
        $process = "Verification";
        if($this->claim->verifier_id==NULL){
            $route = "ot.approval";
            $process = "Approval";
        }
        return [
            'id' => $this->claim->id,
            'param' => '',
            'route_name' => $route,
            'text' => 'Claim ' . $this->claim->refno.' has been submitted for your '.$process,
            'icon' => 'far fa-clock'
          ];
    }
}
