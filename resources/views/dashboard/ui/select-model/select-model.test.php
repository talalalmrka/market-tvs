<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::ui.select-model')
        ->assertStatus(200);
});
