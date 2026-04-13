<?php

use App\Livewire\Components\DashboardDatatable;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Permission;

new #[Title('Permissions')] class extends DashboardDatatable
{
    public function builder()
    {
        return Permission::query();
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
        ];
    }

    public function edit($id)
    {
        $this->authorize('manage_permissions');
        $permission = Permission::findById($id);
        if (!$permission) {
            abort(404);
        }
        $this->dispatch('edit', 'permission', $id);
    }
};
