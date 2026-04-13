<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('auth::reset-password')
        ->assertStatus(200);
});
