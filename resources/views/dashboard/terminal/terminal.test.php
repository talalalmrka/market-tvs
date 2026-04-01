<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::terminal')
        ->assertStatus(200);
});
