@component('mail::message')
# One last step

We just need you to confirm your email address to prove you're human. You get it, right ? Cool !

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
