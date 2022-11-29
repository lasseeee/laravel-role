<?php

use Lasseeee\Role\Models\Role;

return [
    /*
     * This class is the model used for storing configuration on roles.
     *
     * It must be or extend `Lasseeee\Role\Models\Role::class`
     */
    'role_model' => Role::class,

    /*
     * This model used for role name matching.
     */
    'name_column' => 'name',
];
