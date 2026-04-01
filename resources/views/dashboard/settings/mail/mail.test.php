<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::settings.mail')
        ->assertStatus(200);
});
