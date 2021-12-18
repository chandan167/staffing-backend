<?php

namespace App\Http\Controllers\User\Auth;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\SignupRequest;
use App\Services\Otp\OtpService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SignupController extends Controller
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function signup(SignupRequest $request)
    {
        $user = $this->userService->create($request->getData());
        $token = $user->createToken($user->email)->accessToken;
        return ApiResponse::setData([
            'user' => $user,
            'token' => $token
        ])->setStatusCode(Response::HTTP_CREATED)->json();
    }
}
