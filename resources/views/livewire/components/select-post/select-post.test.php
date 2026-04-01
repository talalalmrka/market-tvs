<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('components::select-menu')
        ->assertStatus(200);
});
