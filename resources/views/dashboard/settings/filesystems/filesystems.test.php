<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::settings.filesystems')
        ->assertStatus(200);
});
