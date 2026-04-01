<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::menus.structure')
        ->assertStatus(200);
});
