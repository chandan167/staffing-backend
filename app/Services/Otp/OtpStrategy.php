<?php

namespace App\Services\Otp;

interface OtpStrategy{

    /**
     * Overwrite this method in model and return phone number for send otp
     *
     * @return string|null
     */
    public function getPhone():?string;


    /**
     * Overwrite this method in model and return email id for send otp
     *
     * @return string|null
     */
    public function getEmail():?string;


     /**
     * Overwrite this method in model and return name of the user
     *
     * @return string
     */
    public function getName():string;
}
