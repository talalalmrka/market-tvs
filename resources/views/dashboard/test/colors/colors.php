<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed()]
    public function colors()
    {
        return [
            'primary',
            'secondary',
            'light',
            'dark',
            'red',
            'orange',
            'amber',
            'yellow',
            'lime',
            'green',
            'emerald',
            'teal',
            'cyan',
            'sky',
            'blue',
            'indigo',
            'violet',
            'purple',
            'fuchsia',
            'pink',
            'rose',
            'slate',
            'gray',
            'zinc',
            'neutral',
            'stone',
            'info',
            'success',
            'warning',
            'error',
        ];
    }
    #[Computed()]
    public function ranges()
    {
        return [
            50,
            100,
            200,
            300,
            400,
            500,
            600,
            700,
            800,
            900,
            950,
        ];
    }
};
