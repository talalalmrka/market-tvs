<?php

use App\Livewire\Components\DashboardDatatable;
use App\Livewire\Components\Datatable\Datatable;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Role;

new #[Title('Roles')] class extends DashboardDatatable
{
    public function builder()
    {
        return Role::query();
    }

    public function getColumns()
    {
        return [
            column('name')
                ->label(__('Name'))
                ->sortable()
                ->searchable()
                ->filterable(),
            column('guard_name')
                ->label(__('Guard name'))
                ->sortable()
                ->searchable()
                ->filterable()
                ->class('text-center'),
            column('permissions')
                ->label(__('Permissions'))
                ->content(function (Role $role) {
                    return view('livewire.components.datatable.permissions', [
                        'role' => $role,
                    ]);
                }),
        ];
    }

    public function edit($id)
    {
        $this->authorize('manage_roles');
        $role = Role::findById($id);
        if (!$role) {
            abort(404);
        }
        $this->dispatch('edit', 'role', $id);
    }
};
