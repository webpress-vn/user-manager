<?php

namespace VCComponent\Laravel\User\Entities;

use Illuminate\Database\Eloquent\Model;
use NF\Roles\Models\Role;

class RoleHasPermission extends Model
{
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    protected $table = 'permission_role';

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
