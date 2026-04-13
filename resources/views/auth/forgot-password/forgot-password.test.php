<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('auth::forgot-password')
        ->assertStatus(200);
});
