<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::settings.auth')
        ->assertStatus(200);
});
