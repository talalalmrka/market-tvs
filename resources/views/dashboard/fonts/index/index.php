<?php

use App\Livewire\Components\DashboardDatatable;
use App\Models\Font;
use Livewire\Attributes\Title;

new #[Title('Fonts')] class extends DashboardDatatable
{
    public function builder()
    {
        return Font::query();
    }

    public function getColumns()
    {
        return [
            column('name')
                ->label(__('Name'))
                ->sortable()
                ->searchable()
                ->filterable(),
            column('style')
                ->label(__('Style'))
                ->sortable()
                ->searchable()
                ->filterable(),
            column('weight')
                ->label(__('Weight'))
                ->sortable()
                ->searchable()
                ->filterable(),
            column('display')
                ->label(__('Display'))
                ->sortable()
                ->searchable()
                ->filterable(),
            column('enabled')
                ->label('Enabled')
                ->sortable()
                ->content(function (Font $font) {
                    return view('dashboard::fonts.enabled-cell', ['font' => $font]);
                }),
        ];
    }

    public function edit($id)
    {
        $this->dispatch('edit', 'font', $id);
    }

    public function create()
    {
        $this->dispatch('edit', 'font');
    }

    public function toggleFont($id, $enabled)
    {
        $font = Font::findOrFail($id);
        if ($font) {
            $font->enabled = (bool) $enabled;
            $save = $font->save();
            if ($save) {
                $this->toastSuccess(__('Saved.'));
            } else {
                $this->toastError(__('Failed!'));
            }
        } else {
            $this->toastError(__('Font with id :id not found!', ['id' => $id]));
        }
    }
};
