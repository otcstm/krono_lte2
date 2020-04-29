@component('mail::message')
Dear {{ $grpowner_name }}, 

You have been assigned as Group Owner for Group *{{ $grpname }}*. Please proceed with further action.

Please click on  Overtime System  to access Overtime System.

@component('mail::button', ['url' => $url])
{{ $btn_val }}
@endcomponent

Thanks,<br />
<i>Overtime System</i>
<br />
<br />
<hr />
<small>
This is a system generated email from Overtime System.
If you have any queries / complaints related to overtime, kindly channel them through HC SSO Helpdesk https://precise.tm.com.my (IDM Login & Password > Incident Catalog > HCSSO – Helpdesk).
Please do not reply to this email.
</small>
@endcomponent