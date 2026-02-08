<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('site::screens.index')
        ->assertStatus(200);
});
