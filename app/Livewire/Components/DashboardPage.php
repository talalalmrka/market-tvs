<?php

namespace App\Livewire\Components;

use App\Traits\WithToast;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts::app.sidebar')]
abstract class DashboardPage extends Component
{
    use WithToast, WithFileUploads;
    public string $title = 'Dashboard';
}
