<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::config.index')
        ->assertStatus(200);
});
