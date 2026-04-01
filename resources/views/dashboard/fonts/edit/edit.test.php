<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::fonts.edit')
        ->assertStatus(200);
});
