<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

if (! function_exists('role')) {
    /**
     * resolve role
     *
     * @param  int|string|Spatie\Permission\Models\Role  $role
     * @return Spatie\Permission\Models\Role|null
     */
    function role($role)
    {
        if ($role instanceof Role) {
            return $role;
        }
        if (is_int($role) || is_numeric($role)) {
            return Role::find($role);
        }

        if (is_string($role)) {
            return Role::firstWhere('name', $role);
        }

        return null;
    }
}
if (! function_exists('role_options')) {
    function role_options(string $guard_name = 'web'): array
    {
        return Role::where('guard_name', $guard_name)
            ->get()
            ->map(fn (Role $role) => [
                'value' => $role->name,
                'label' => $role->name,
            ])
            ->toArray();
    }
}

if (! function_exists('user_has_role')) {
    function user_has_role(int|User $user, string|int|Role $role): bool
    {
        // Resolve user
        if (is_int($user)) {
            $user = User::find($user);
        }

        if (! $user) {
            return false;
        }

        // Resolve role name
        if ($role instanceof Role) {
            $role = $role->name;
        } elseif (is_int($role)) {
            $role = Role::find($role)?->name;
        }

        if (! $role) {
            return false;
        }

        return $user->hasRole($role);
    }
}

if (! function_exists('current_user_has_role')) {
    function current_user_has_role(string|int|Role $role): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return user_has_role($user, $role);
    }
}

if (! function_exists('role_label')) {
    /**
     * get role label
     *
     * @param  int|string|Spatie\Permission\Models\Role  $role
     * @return string|null
     */
    function role_label($role)
    {
        $role = role($role);
        if ($role) {
            $translateKey = "models.roles.{$role->name}";
            $translatedText = __($translateKey);
            $translatedText = $translatedText !== $translateKey ? $translatedText : ucfirst($role->name);
            $label = $translatedText;

            return is_string($label)
                ? $label
                : ucfirst($role->name);
        }

        return null;
    }
}

if (! function_exists('role_icon')) {
    /**
     * get role label
     *
     * @param  int|string|Spatie\Permission\Models\Role  $role
     * @return string|null
     */
    function role_icon($role)
    {
        $role = role($role);
        if ($role) {
            $icon = config("icons.{$role->name}");

            return is_string($icon)
                ? $icon
                : null;
        }

        return null;
    }
}

if (! function_exists('role_color')) {
    /**
     * get role color
     *
     * @param  int|string|Spatie\Permission\Models\Role  $role
     * @return string|null
     */
    function role_color($role)
    {
        $role = role($role);
        if ($role) {
            $color = config("colors.{$role->name}");

            return is_string($color)
                ? $color
                : null;
        }

        return null;
    }
}
