<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::ui.icon-picker')
        ->assertStatus(200);
});
