<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('site::fullscreen')
        ->assertStatus(200);
});
