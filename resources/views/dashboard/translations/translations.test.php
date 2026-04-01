<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::translations')
        ->assertStatus(200);
});
