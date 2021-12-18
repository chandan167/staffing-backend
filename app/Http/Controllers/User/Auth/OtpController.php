<?php

namespace App\Http\Controllers\User\Auth;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Otp\OtpVerifyRequest;
use App\Services\Otp\OtpService;
use App\Services\UserService;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    private OtpService $otpService;

    public function __construct(UserService $userService, OtpService $otpService)
    {
        $this->userService = $userService;
        $this->otpService = $otpService;
    }

    public function sendOtp(Request $request)
    {
        $this->otpService->setUser($request->user())->sendOnEmail();
        return ApiResponse::setMessage('send otp successful')->json();
    }


    public function verify(OtpVerifyRequest $request)
    {
        $user = $request->user();
        $this->otpService->setEmail($user->email)->setOtp($request->getOtp())->verify();
        $user->makeEmailVerify();
        return ApiResponse::setData([
            'user' => $user
        ])->json();
    }
}
