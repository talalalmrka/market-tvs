<?php

use App\Livewire\Components\DashboardDatatable;
use App\Models\Screen;
use Livewire\Attributes\Title;

new #[Title('Screens')] class extends DashboardDatatable
{
    public string $title = 'Screens';
    public function builder()
    {
        return current_user_has_role('admin')
            ? Screen::query()
            : Screen::withUser(current_user_id());
    }
    public function getColumns()
    {
        return [
            column('name')
                ->label(__('Name'))
                ->sortable()
                ->searchable(),

            column('uuid')
                ->label(__('UUID'))
                ->sortable()
                ->searchable()
                ->filterable(),

            column('time_slots')
                ->label(__('Time slots'))
                ->sortable()
                ->content(fn(Screen $screen) => $screen->timeSlots()->count()),

            column('is_active')
                ->label(__('Active'))
                ->sortable()
                ->content(fn(Screen $screen) => $screen->timeSlots()->count()),
        ];
    }
    public function getActions()
    {
        return [
            taction('show')
                ->icon('bi-eye')
                ->title(__('Show'))
                ->target('_blank')
                ->href(fn(Screen $screen) => $screen->permalink),

            taction('edit')
                ->icon('bi-pencil-square')
                ->title(__('Edit')),

            taction('delete')->icon('bi-trash')->title(__('Delete')),
        ];
    }
    public function edit($id)
    {
        $this->redirect(route('dashboard.screens.edit', $id), true);
    }
};
