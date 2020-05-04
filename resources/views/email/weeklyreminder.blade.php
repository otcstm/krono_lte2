@component('mail::message')
Dear **{{ $name }}**,

There are still some pending items that require your attention in Overtime System.

@component('mail::panel')
@component('mail::table')
| Action | Item Count |
| :------------ |:-------------:|
@foreach($pendings as $item)
| {{ $item->type }} | {{ $item->count }} |
@endforeach
@endcomponent
@endcomponent

@component('mail::button', ['url' => route('misc.home')])
Go to Overtime System
@endcomponent

This is a system generated email from Overtime System.

If you have any queries / complaints related to overtime, kindly channel them through [HC SSO Helpdesk](https://precise.tm.com.my) (IDM Login & Password > Incident Catalog > HCSSO – Helpdesk).

Please do not reply to this email.
@endcomponent
