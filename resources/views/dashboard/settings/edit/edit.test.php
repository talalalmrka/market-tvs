<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::settings.edit')
        ->assertStatus(200);
});
