<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::ui.alpine-sort')
        ->assertStatus(200);
});
