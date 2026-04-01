<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::menus.add')
        ->assertStatus(200);
});
