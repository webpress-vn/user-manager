<?php

namespace NF\Roles\Contracts;

interface PermissionGroupHasRelations
{
    /**
     * PermissionGroup belongs to many Permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions();
}
