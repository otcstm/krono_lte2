@component('mail::message')

Dear **{{$toname}}**,

Your overtime application **{{ $claim }}** has been queried.<br>
Please amend your claim request and submit by {{$date}}.

Reason: **{{$reason}}**

Please click on [Overtime System](https://ot.tm.com.my/) to access Overtime System.

This is a system generated email from [Overtime System](https://ot.tm.com.my/).
If you have any queries / complaints related to overtime, kindly channel them through [HC SSO Helpdesk](https://precise.tm.com.my/) (IDM Login & Password > Incident Catalog > HCSSO - Helpdesk).
Please do not reply to this email.

@endcomponent
