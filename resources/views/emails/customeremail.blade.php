@component('mail::message')
# {{ $details['title'] }}

@component('mail::button', ['url' => $details['url']])
Ticket Link
@endcomponent
   
Thanks,<br>
{{ config('app.name') }}
@endcomponent