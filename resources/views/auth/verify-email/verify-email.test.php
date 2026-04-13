<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('auth::verify-email')
        ->assertStatus(200);
});
