@component('mail::message')
{{ __('A new lick has been shared with you. You can see it in the link below and in the Shared with Me page:') }}

@component('mail::button', ['url' => $url])
{{ __('View Lick') }}
@endcomponent

{{ __('That is all. Thank you for using Helicksr.') }}
@endcomponent
