<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('components::select-model')
        ->assertStatus(200);
});
