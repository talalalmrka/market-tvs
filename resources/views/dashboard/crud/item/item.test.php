<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::crud.item')
        ->assertStatus(200);
});
