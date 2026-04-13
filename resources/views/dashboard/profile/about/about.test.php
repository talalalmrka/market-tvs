<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::profile.about')
        ->assertStatus(200);
});
