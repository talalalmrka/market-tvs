<?php

use App\Traits\WithToast;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    use WithToast;
    public $basicToast = [
        'message' => 'This is dispatched toast',
        'options' => [
            'type' => 'info',
        ],
    ];
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
