<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::profile.index')
        ->assertStatus(200);
});
