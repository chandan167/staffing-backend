<?php

namespace App\Services;

use App\Models\User\User;

class UserService{


    private User $user;


    public function __construct(User $user)
    {
        $this->user = $user;
    }


     /**
     * Proxy a scope call onto the query builder.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->user->$method(...$parameters);
    }

}
