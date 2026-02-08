<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::scrs.index')
        ->assertStatus(200);
});
