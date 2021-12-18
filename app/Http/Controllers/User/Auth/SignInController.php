<?php

namespace App\Http\Controllers\User\Auth;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\SignInRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class SignInController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function signIn(SignInRequest $request)
    {
        $user = $this->userService->whereEmail($request->email)->first();
        if ($user && $user->verifyPassword($request->getPassword())) {
            $token = $user->createToken($user->email)->accessToken;
            return ApiResponse::setData([
                'user' => $user,
                'token' => $token
            ])->json();
        }
        return ApiResponse::setMessage(__('auth.failed'))->json();
    }
}
