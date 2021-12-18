<?php

namespace App\Mail;

use App\Services\Otp\OtpContract;
use App\Services\Otp\OtpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtp extends Mailable
{
    use Queueable, SerializesModels;

    private OtpService $otpService;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(OtpContract $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name') . " " . __('otp.email'))->markdown('emails.otp', ['otpService' => $this->otpService]);
    }
}
