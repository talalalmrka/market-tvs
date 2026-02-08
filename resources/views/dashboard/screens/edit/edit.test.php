<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::scrs.edit')
        ->assertStatus(200);
});
