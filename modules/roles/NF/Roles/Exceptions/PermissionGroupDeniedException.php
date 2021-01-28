<?php

namespace NF\Roles\Exceptions;

class PermissionGroupDeniedException extends AccessDeniedException
{
    /**
     * Create a new permission denied exception instance.
     *
     * @param string $permission
     */
    public function __construct($permission_group)
    {
        $this->message = sprintf("You don't have a required ['%s'] permission group.", $permission_group);
    }
}
