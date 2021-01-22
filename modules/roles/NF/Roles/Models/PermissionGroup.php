<?php

namespace NF\Roles\Models;

use Illuminate\Database\Eloquent\Model;
use NF\Roles\Contracts\PermissionGroupHasRelations as PermissionGroupHasRelationsContract;
use NF\Roles\Traits\PermissionGroupHasRelations;
use NF\Roles\Traits\Slugable;
use NF\Roles\Models\Permission;

class PermissionGroup extends Model implements PermissionGroupHasRelationsContract
{
    use Slugable, PermissionGroupHasRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];
    protected $table = "permission_group";
    /**
     * Create a new model instance.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if ($connection = config('role.connection')) {
            $this->connection = $connection;
        }
    }
    public function permissions(){
        return $this->hasMany(permission::class);
    }
}
