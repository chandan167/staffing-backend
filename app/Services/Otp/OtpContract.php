<?php

namespace App\Services\Otp;

interface OtpContract
{

    /**
     * Overwrite and return user name
     *
     * @return string
     */
    public function getUserName(): string;

    /**
     * Overwrite and return otp value
     *
     * @return int
     */
    public function getOtp(): int;

    /**
     * Overwrite and return expire time in minute
     *
     * @return int
     */
    public function getExpireTime(): int;
}
