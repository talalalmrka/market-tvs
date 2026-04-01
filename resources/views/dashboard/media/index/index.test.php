<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::media.index')
        ->assertStatus(200);
});
