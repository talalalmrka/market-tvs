<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::menus.index')
        ->assertStatus(200);
});
