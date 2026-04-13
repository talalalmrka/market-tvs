<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::profile.avatar')
        ->assertStatus(200);
});
