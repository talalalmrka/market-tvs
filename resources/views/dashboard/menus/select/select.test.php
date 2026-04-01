<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::menus.select')
        ->assertStatus(200);
});
