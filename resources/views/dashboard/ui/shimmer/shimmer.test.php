<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::ui.shimmer')
        ->assertStatus(200);
});
