<?php

namespace VCComponent\Laravel\User\Entities;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use NF\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;
use NF\Roles\Traits\HasRoleAndPermission;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;
use VCComponent\Laravel\User\Contracts\UserManagement;
use VCComponent\Laravel\User\Contracts\UserSchema;
use VCComponent\Laravel\User\Entities\Gender;
use VCComponent\Laravel\User\Notifications\MailResetPasswordToken;
use VCComponent\Laravel\User\Traits\UserManagementTrait;
use VCComponent\Laravel\User\Traits\UserSchemaTrait;

class User extends Model implements AuthenticatableContract, JWTSubject, Transformable, UserManagement, UserSchema, CanResetPasswordContract, HasRoleAndPermissionContract
{
    use Authenticatable,
    TransformableTrait,
    UserManagementTrait,
    UserSchemaTrait,
    Notifiable,
    CanResetPassword,
        HasRoleAndPermission;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'username',
        'phone_number',
        'address',
        'first_name',
        'last_name',
        'avatar',
        'password',
        'verify_token',
        'gender',
        'birth',
        'status',
        'email_verified_at',
        "email_verified",
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
    public const SUPER_ADMIN_USER = 'super_admin';
    public const SUPER_ADMIN_ID = 1;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getEmailVerifyToken()
    {
        return Hash::make($this->email);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token));
    }

    public function getToken()
    {
        return JWTAuth::fromUser($this);
    }

    public function sex()
    {
        return $this->hasOne(Gender::class, 'id', 'gender');
    }
}
