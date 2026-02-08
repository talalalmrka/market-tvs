<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('site::screens.show')
        ->assertStatus(200);
});
