<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('site::home')
        ->assertStatus(200);
});
