@component('mail::message')
## Hello

You have been assigned as group owner for *{{ $grpname }}*

@component('mail::button', ['url' => $url])
View Shift Group Page
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
