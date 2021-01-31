<?php

namespace Lasseeee\Role\Concerns;

use Lasseeee\Role\Models\Role;

trait UsesRoleModel
{
    public function getRoleModel(): Role
    {
        $roleModelClass = config('role.role_model');

        return new $roleModelClass;
    }
}
