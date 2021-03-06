<?php

namespace App\Services\Otp;

use Exception;
use Carbon\Carbon;
use App\Mail\SendOtp;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OtpService implements OtpContract
{

    /**
     * hold phone number
     * @var string|null $phone
     */
    private  $phone = null;


    /**
     * hold user name
     * @var string $name
     */
    private string  $name = '';


    /**
     * hold email id
     * @var string|null $email
     */
    private  $email = null;

    /**
     * hold token id
     * @var string|null $token
     */
    private  $token = null;

    /**
     * hold otp
     * @var int $otp
     */
    private int $otp;


    /**
     * hold Modal object
     * @var Model $modal
     */
    private Model $model;


    /**
     * hold otp expire time in min
     * @var int $expire
     */
    private int $expire = 10;  // 10 min

    /**
     * hold otp database record generated by phone
     */
    private $otpData;


    /**
     * hold otp database table name
     *
     * @var const OTP_TABLE
     */
    const OTP_TABLE = 'password_resets';

    /**
     * Set phone value
     *
     * @param string|null $phone
     * @return self
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }


    /**
     * Set token value
     *
     * @param string|null $token
     * @return self
     */
    public function setToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get token value
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }


    /**
     * Set email value
     *
     * @param string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

     /**
     * Get email value
     * @return string email
     */
    public function getEmail(): string
    {
       return $this->email;
    }

    /**
     * Set otp expire time in min
     *
     * @param int $phone
     * @return self
     */
    public function setExpireTime(int $expire): self
    {
        $this->expire = $expire;
        return $this;
    }

    /**
     * return expire time in min
     *
     * @return int
     */
    public function getExpireTime(): int
    {
        return  $this->expire;
    }

    /**
     * Set Eloquent Model object
     *
     * @param  Model $model
     * @return self
     */
    public function setUser(Model $model): self
    {
        $this->model = $model;
        $this->name = $model->getName();
        return $this;
    }

    /**
     * Set user name
     *
     * @param  string $name
     * @return self
     */
    public function setUserName($name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get user name
     *
     * @return string
     */
    public function getUserName(): string
    {
        return $this->name;
    }


    /**
     * Set otp value
     *
     * @param  int $otp
     * @return self
     */
    public function setOtp(int $otp): self
    {
        $this->otp = $otp;
        return $this;
    }


    /**
     * get otp value
     *
     * @return int
     */
    public function getOtp(): int
    {
        return $this->otp;
    }

    /**
     * handle generate otp, add record in database and send otp on phone
     *
     * @return self
     */
    public function sendOPhone(): self
    {
        $this->generateOtp();
        $this->email = null;
        if (!$this->phone) {
            $this->phone = $this->model->getPhone();
        }
        $this->deleteOldOtp();
        $this->create();
        $this->sendMessage();

        return $this;
    }

    /**
     * handle generate otp, add record in database and send otp on phone
     *
     * @return self
     */
    public function sendOnEmail(): self
    {
        $this->generateOtp();
        $this->phone = null;
        if (!$this->email) {
            $this->email = $this->model->getEmail();
        }
        $this->deleteOldOtp();
        $this->create();
        $this->sendMessage();

        return $this;
    }

    /**
     * Generate Otp and assign in class otp property
     *
     * @param void
     * @return void
     */
    private function generateOtp()
    {
        $this->otp = mt_rand(1000, 9999);
    }


    /**
     * handle record in database
     *
     * @return self
     */
    private function create()
    {
        DB::table(self::OTP_TABLE)->insert([
            'otp' => $this->otp,
            'phone' => $this->phone,
            'email' => $this->email,
            'expire' => $this->calculateExpireTime()
        ]);
    }

    /**
     * handle calculate the otp expire time in millisecond
     *
     * @return int return expire time in millisecond
     */
    private function calculateExpireTime(): int
    {
        return (int) Carbon::now()->addMinutes($this->expire)->getPreciseTimestamp(3);
    }



    /**
     * handle send otp on phone number
     *
     * @return void
     */
    private function sendMessage()
    {
        if ($this->email) {
            $this->sendEmail();
        } else if ($this->phone) {
            $this->sendSms();
        }
    }



    public function destroy()
    {
        $this->deleteOldOtp();
    }


    public function generateToken(): self
    {
        $this->token = Str::random(150);
        $this->expire = $this->calculateExpireTime();

        if ($this->email) {
            DB::table(self::OTP_TABLE)->where('email', $this->email)->update([
                'expire' => $this->expire,
                'token' => $this->token,
                'otp' => null
            ]);
        } else if ($this->phone) {
            DB::table(self::OTP_TABLE)->where('phone', $this->phone)->update([
                'expire' => $this->expire,
                'token' => $this->token,
                'otp' => null
            ]);
        }
        return $this;
    }



    /**
     * handle the delete old otp
     *
     * @return void
     */
    private function deleteOldOtp()
    {
        if ($this->phone) {
            DB::table(self::OTP_TABLE)->where('phone', $this->phone)->delete();
        } else if ($this->email) {
            DB::table(self::OTP_TABLE)->where('email', $this->email)->delete();
        }else if($this->token){
            DB::table(self::OTP_TABLE)->where('token', $this->token)->delete();
        }
    }

    /**
     * handle the otp verification
     *
     * @return self|$this
     */
    public function verify(): self
    {
        // get otp data in database
        $this->getOtpData();
        // verify otp database record otp and request otp
        $this->verifyOtp();
        // verify expire time
        $this->verifyExpireTime();
        // delete old otp
        // $this->deleteOldOtp();
        return $this;
    }

    public function verifyToken()
    {
        // get otp data in database
        $this->getOtpData();

        if ($this->token !== $this->otpData->token) {
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, __('otp.token'));
        }

        // verify expire time
        $this->verifyExpireTime();

        // delete old otp
        // $this->deleteOldOtp();
        return $this;
    }

    /**
     * handle find the record in database related to phone
     *
     * @return void
     * @throws HttpException
     */
    private function getOtpData()
    {
        $otpData = null;
        if ($this->email) {
            $otpData = DB::table(self::OTP_TABLE)->where('email', $this->email)->first();
            if (!$otpData) {
                throw new HttpException(Response::HTTP_NOT_FOUND, __('otp.not_found', ['attribute' => $this->email]));
            }
        } else if ($this->phone) {
            $otpData = DB::table(self::OTP_TABLE)->where('phone', $this->phone)->first();
            if (!$otpData) {
                throw new HttpException(Response::HTTP_NOT_FOUND, __('otp.not_found', ['attribute' => $this->phone]));
            }
        } else if($this->token){
            $otpData = DB::table(self::OTP_TABLE)->where('token', $this->token)->first();
            if (!$otpData) {
                throw new HttpException(Response::HTTP_NOT_FOUND, __('otp.token', ['attribute' => $this->phone]));
            }
        }

        if (!$otpData) {
            throw new Exception('set email phone, token any one');
        }

        $this->otpData = $otpData;
        $this->email = $otpData->email;
        $this->ptone = $otpData->phone;
    }


    /**
     * handle the match database otp and request otp
     *
     * @return void
     * @throws HttpException
     */
    private function verifyOtp()
    {
        if ($this->otp !== (int)$this->otpData->otp) {
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, __('otp.invalid'));
        }
    }


    /**
     * handle the verify otp expire time
     *
     * @return void
     * @throws HttpException
     */
    public function verifyExpireTime()
    {
        $now = (int) Carbon::now()->getPreciseTimestamp(3);
        if ($this->otpData->expire < $now) {
            $this->deleteOldOtp();
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY,  __('otp.expire'));
        }
    }


    /**
     * handle the send otp on email
     *
     * @return void
     */
    private function sendEmail()
    {
        Mail::to($this->email)->send(new SendOtp($this));
    }


    /**
     * handle the send otp on phone
     *
     * @return void
     */
    private function sendSms()
    {
        //TODO : write code to send otp on phone
    }
}
