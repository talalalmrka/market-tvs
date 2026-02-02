<?php

namespace App\Livewire\Components;

use App\Livewire\Components\Datatable\Datatable;
use App\Traits\WithToast;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
abstract class DashboardDatatable extends Datatable
{
    use WithToast, WithFileUploads;
    public string $title = 'Dashboard';
}
