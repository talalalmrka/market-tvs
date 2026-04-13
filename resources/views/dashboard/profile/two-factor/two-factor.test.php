<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::profile.two-factor')
        ->assertStatus(200);
});
