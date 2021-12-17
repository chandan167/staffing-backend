<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;

class AuthFactory{

    const API_USER = 'api_user';


    public static function userAuthApiGuard():StatefulGuard
    {
        return self::baseAuth('api_user');
    }


    public static function baseAuth($guard = null): Guard
    {
        return Auth::guard($guard);
    }
}
