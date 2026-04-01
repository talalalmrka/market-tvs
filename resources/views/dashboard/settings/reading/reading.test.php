<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::settings.reading')
        ->assertStatus(200);
});
