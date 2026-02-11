<?php

namespace App\Livewire\Components;

use App\Traits\WithToast;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts::app.curve')]
abstract class SitePage extends Component
{
    use WithToast, WithFileUploads;
    public string $title = 'Home';
}
