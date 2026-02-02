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
};
