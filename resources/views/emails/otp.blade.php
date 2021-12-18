@component('mail::message')
# {{config('app.name')}}

### Hi {{$otpService->getUserName()}},

Please do not share otp with any one .

## {{$otpService->getOtp()}}

### OTP will expire in {{$otpService->getExpireTime()}} minute.



Thanks,<br>
{{ config('app.name') }}
@endcomponent
