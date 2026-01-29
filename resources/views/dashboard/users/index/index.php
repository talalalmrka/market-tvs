<?php

use App\Livewire\Components\Datatable\Datatable;
use App\Models\User;
use Livewire\Component;

new class extends Datatable
{
    public function builder()
    {
        return User::query();
    }
    public function getColumns()
    {
        return [
            column('name')
                ->label(__('Name'))
                ->sortable()
                ->content(function (User $user) {
                    return $user->name;
                    /* return thumbnail([
                        'title' => $user?->display_name,
                        'image' => $user?->getFirstMediaUrl('avatar'),
                    ]); */
                }),

            column('email')
                ->label(__('Email'))
                ->sortable()
                ->searchable()
                ->filterable(),
            // ->content(fn(User $user) => a(['href' => "mailto:{$user->email}", 'label' => $user->email, 'target' => '_blank'])),

            /* column('phone')
                ->label(__('Phone'))
                ->content(fn(User $user) => a(['href' => "tel:{$user->getMeta('phone')}", 'label' => $user->getMeta('phone') ?? '', 'target' => '_blank'])), */

            column('role')
                ->label(__('Role'))
                ->sortable()
                ->searchable()
                ->filterable()
                ->content(function (User $user) {
                    return '';
                    // return view('livewire.components.datatable.roles', ['user' => $user]);

                }),
        ];
    }
    public function edit($id)
    {
        $this->redirect(route('profile.edit'), true);
    }

    public function create()
    {
        $this->dispatch('edit', 'user');
    }
    public function render()
    {
        return view('dashboard.users.index.index')->layout('layouts.dashboard', [
            'title' => __('Users'),
        ]);
    }
};
