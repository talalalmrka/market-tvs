<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

if (!function_exists('role_options')) {
    function role_options(string $guard_name = 'web'): array
    {
        return Role::where('guard_name', $guard_name)
            ->get()
            ->map(fn(Role $role) => [
                'value' => $role->name,
                'label' => $role->name,
            ])
            ->toArray();
    }
}

if (!function_exists('user_has_role')) {
    function user_has_role(int|User $user, string|int|Role $role): bool
    {
        // Resolve user
        if (is_int($user)) {
            $user = User::find($user);
        }

        if (!$user) {
            return false;
        }

        // Resolve role name
        if ($role instanceof Role) {
            $role = $role->name;
        } elseif (is_int($role)) {
            $role = Role::find($role)?->name;
        }

        if (!$role) {
            return false;
        }

        return $user->hasRole($role);
    }
}

if (!function_exists('current_user_has_role')) {
    function current_user_has_role(string|int|Role $role): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        return user_has_role($user, $role);
    }
}
