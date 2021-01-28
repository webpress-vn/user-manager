<?php

namespace NF\Roles\Traits;

trait PermissionGroupHasRelations
{
    /**
     * Permission belongs to many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissionGroup()
    {
        return $this->belongsToMany(config('roles.models.permission'))->withTimestamps();
    }
}
