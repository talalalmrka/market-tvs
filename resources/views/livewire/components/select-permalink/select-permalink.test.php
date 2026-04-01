<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('components::select-permalink')
        ->assertStatus(200);
});
