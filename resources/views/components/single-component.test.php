<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('single-component')
        ->assertStatus(200);
});
