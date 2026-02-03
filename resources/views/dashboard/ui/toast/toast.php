<?php

use App\Livewire\Components\DashboardPage;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

new #[Title('Toast')] class extends DashboardPage
{
    #[Computed()]
    public function types(): array
    {
        return [
            'info',
            'success',
            'warning',
            'error',
        ];
    }

    #[Computed()]
    public function positions(): array
    {
        return [
            'top-start',
            'top-center',
            'top-end',
            'center-start',
            'center',
            'center-end',
            'bottom-start',
            'bottom-center',
            'bottom-end',
        ];
    }

    #[Computed()]
    public function sizes(): array
    {
        return [
            'default',
            'xxs',
            'xs',
            'sm',
            'lg',
            'xl',
            'xxl',
        ];
    }

    #[Computed()]
    public function buttonClass($type)
    {
        return match ($type) {
            'info' => 'btn-info',
            'success' => 'btn-success',
            'warning' => 'btn-warning',
            'error' => 'btn-error',
            default => 'btn-primary',
        };
    }

    #[Computed()]
    public function positionLabel($position)
    {
        return Str::headline($position);
    }

    public function showToastType(string $type)
    {
        $this->toast(__("This is toast type: :type", ['type' => $type]), [
            'type' => $type,
        ]);
    }
    public function showToastPosition(string $position)
    {
        $this->toast(__("This is toast position: :position", ['position' => $position]), [
            'position' => $position,
        ]);
    }
    public function showToastSize(string $size)
    {
        $this->toast(__("This is toast size: :size", ['size' => $size]), [
            'size' => $size,
        ]);
    }
};
