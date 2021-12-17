<?php

namespace App\Http\Controllers\User\Auth;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\SignupRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class SignupController extends Controller
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function signup(SignupRequest $request)
    {
        $user = $this->userService->findOrFail(256);
        $user = $this->userService->create($request->getData());
        return ApiResponse::setData(['user' => $user])->json();
    }
}
