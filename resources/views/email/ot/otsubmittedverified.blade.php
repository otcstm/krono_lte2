@component('mail::message')
## Dear <b>{{$toname}}</b>,

Overtime application <b>{{ $claim }}</b> for <b>{{$appname}}</b> has been {{$extra}}routed to you for your {{$type}}. 

Please click on KRONO System and sign in to the KRONO System to process this application. 

@component('mail::button', ['url' => $url])
View Overtime Claim
@endcomponent

This is a system generated email from Overtime System.
If you have any queries / complaints related to overtime, kindly channel them through IRIS: <a href="iris.tm.com.my">IRIS</a> (IDM Login & Password > Incident Catalog > HCSSO - Helpdesk).
Please do not reply to this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
