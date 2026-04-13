@props(['user', 'size' => 'sm', 'pill' => true, 'outline' => true])
<div class="inline-flex flex-wrap gap-2">
    @foreach ($user->getRoleNames() as $role)
        @php
            $label = is_string(__("models.roles.{$role}")) ? __("models.roles.{$role}") : $role;
            $icon = is_string(config("icons.{$role}")) ? config("icons.{$role}") : null;
            $color = is_string(config("colors.{$role}")) ? config("colors.{$role}") : null;
        @endphp
        <fgx:badge :label="$label" :icon="$icon" :color="$color" :size="$size"
            :pill="$pill" :outline="$outline" />
    @endforeach
</div>
