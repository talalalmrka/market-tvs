<?php

namespace App\Livewire\Components;

use App\Traits\WithToast;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::auth')]
abstract class AuthPage extends Component
{
    use WithToast;

    public string $title = 'Home';
}
