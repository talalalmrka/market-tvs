<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::ui.menu-group')
        ->assertStatus(200);
});
