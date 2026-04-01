<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('components::select-category')
        ->assertStatus(200);
});
