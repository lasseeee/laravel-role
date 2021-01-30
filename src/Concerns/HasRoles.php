<?php

namespace Lasseeee\Role\Concerns;

use Lasseeee\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

trait HasRoles
{
    /**
     * A model may have multiple roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)
        ->withTimestamps();
    }

    /**
     * Scope the model query to certain roles only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array|\Illuminate\Support\Collection $roles
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRole(Builder $query, $roles): Builder
    {
        if ($roles instanceof Collection) {
            $roles = $roles->all();
        }

        if (! is_array($roles)) {
            $roles = [$roles];
        }

        $roles = array_map(function ($role) {
            if ($role instanceof Role) {
                return $role;
            }

            return Role::firstWhere('name', $role);
        }, $roles);

        return $query->whereHas('roles', function (Builder $subQuery) use ($roles) {
            $subQuery->whereIn('roles.id', \array_column($roles, 'id'));
        });
    }

    /**
     * Assign the given roles to the model.
     *
     * @param array|string  ...$roles
     * @return $this
     */
    public function assignRoles(...$roles)
    {
        $this->roles()->attach(
            $this->findRoles($roles)
        );

        $this->load('roles');

        return $this;
    }

    /**
     * Remove the given roles from the model.
     *
     * @param array|string  ...$roles
     * @return $this
     */
    public function removeRoles(...$roles)
    {
        $this->roles()->detach(
            $this->findRoles($roles)
        );

        $this->load('roles');

        return $this;
    }

    /**
     * Remove all roles and set the given ones.
     *
     * @param  array|string  ...$roles
     *
     * @return $this
     */
    public function syncRoles(...$roles)
    {
        $this->roles()->detach();

        return $this->assignRoles($roles);
    }

    /**
     * Determine if the model has the given role.
     *
     * @param string|\App\Models\Role $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        if ($role instanceof Role) {
            return $this->roles->contains('id', $role->id);
        }
    }

    /**
     * Determine if the model has any of the given roles.
     *
     * @param string|array|\Illuminate\Support\Collection ...$roles
     * @return bool
     */
    public function hasAnyRole(...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        if (empty($roles)) {
            if ($this->roles->isNotEmpty()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the model has all of the given roles.
     *
     * @param  string|array|\Illuminate\Support\Collection  ...$roles
     * @return bool
     */
    public function hasAllRoles(...$roles): bool
    {
        return collect($roles)
        ->map(function ($role) {
            return $role instanceof Role ? $role->name : $role;
        })
        ->diff(
            $this->roles->pluck('name')
        )
        ->isEmpty();
    }

    /**
     * Find role in storage.
     *
     * @param  string|\App\Models\Role $role
     * @return \App\Models\Role
     */
    protected function findRole($role): Role
    {
        if (is_string($role)) {
            return Role::firstWhere('name', $role);
        }

        return $role;
    }

    /**
     * Find the roles in storage.
     *
     * @param  string|array|\Illuminate\Support\Collection ...$roles
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function findRoles(...$roles): Collection
    {
        return collect($roles)
        ->flatten()
        ->map(function ($role) {
            return $this->findRole($role);
        })
        ->filter(function ($role) {
            return $role instanceof Role;
        });
    }
}
