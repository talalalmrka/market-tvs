<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::roles.edit')
        ->assertStatus(200);
});
