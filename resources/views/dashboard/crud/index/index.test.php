<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::crud.index')
        ->assertStatus(200);
});
