<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::fonts.index')
        ->assertStatus(200);
});
