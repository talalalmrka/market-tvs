<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::profile.contact')
        ->assertStatus(200);
});
