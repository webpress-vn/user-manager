<?php

namespace VCComponent\Laravel\User\Entities;

use Illuminate\Database\Eloquent\Model;
use NF\Roles\Models\Role;

class UserHasRole extends Model
{
    protected $fillable = [
        'role_id',
        'user_id',
    ];

    protected $table = 'role_user';

    public function user()
    {
        $user_entity = config('auth.providers.users.model');
        return $this->belongsTo($user_entity, 'user_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
