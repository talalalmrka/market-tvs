<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::settings.broadcasting')
        ->assertStatus(200);
});
