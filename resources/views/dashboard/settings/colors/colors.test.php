<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::settings.colors')
        ->assertStatus(200);
});
