<?php

namespace Lasseeee\Role;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Lasseeee\Role\Role
 */
class RoleFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-role';
    }
}
