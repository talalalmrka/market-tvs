<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::users.index')
        ->assertStatus(200);
});
