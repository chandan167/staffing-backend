<?php

namespace App\Models\User;

use App\Services\Otp\OtpStrategy;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticate implements OtpStrategy
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Relationship
     */


    public function address(): HasOne
    {
        return $this->hasOne(UserAddress::class);
    }



    /**
     * Overwrite this method in model and return phone number for send otp
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }


    /**
     * Overwrite this method in model and return email id for send otp
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }


    /**
     * Overwrite this method in model and return name of the user
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    public function makeEmailVerify(): self
    {
        $this->email_verified_at = Carbon::now();
        $this->save();
        $this->fresh();
        return $this;
    }


    public function verifyPassword(string $password):bool
    {
        return Hash::check($password, $this->password);
    }

    public function create(array $attributes = []): self
    {
        if (array_key_exists('password', $attributes)) {
            $attributes['password'] = Hash::make($attributes['password']);
        }
        return parent::create($attributes);
    }


    public static function getNotFoundMessage(): string
    {
        return __('notFound.user');
    }
}
