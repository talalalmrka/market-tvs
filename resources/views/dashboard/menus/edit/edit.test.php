<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::menus.edit')
        ->assertStatus(200);
});
