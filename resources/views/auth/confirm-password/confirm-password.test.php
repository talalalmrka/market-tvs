<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('auth::confirm-password')
        ->assertStatus(200);
});
