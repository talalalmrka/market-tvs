<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::permissions.edit')
        ->assertStatus(200);
});
