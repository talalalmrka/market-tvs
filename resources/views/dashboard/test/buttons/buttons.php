<?php

use App\Models\Screen;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{

    #[Computed()]
    public function buttons()
    {
        return collect([
            [
                'label' => __('Primary'),
                'class' => 'btn-primary',
            ],
            [
                'label' => __('Secondary'),
                'class' => 'btn-secondary',
            ],
            [
                'label' => __('Light'),
                'class' => 'btn-light',
            ],
            [
                'label' => __('Dark'),
                'class' => 'btn-dark',
            ],
            [
                'label' => __('Red'),
                'class' => 'btn-red',
            ],
            [
                'label' => __('Orange'),
                'class' => 'btn-orange',
            ],
            [
                'label' => __('Amber'),
                'class' => 'btn-amber',
            ],
            [
                'label' => __('Yellow'),
                'class' => 'btn-yellow',
            ],
            [
                'label' => __('Lime'),
                'class' => 'btn-lime',
            ],
            [
                'label' => __('Green'),
                'class' => 'btn-green',
            ],
            [
                'label' => __('Emerald'),
                'class' => 'btn-emerald',
            ],
            [
                'label' => __('Teal'),
                'class' => 'btn-teal',
            ],
            [
                'label' => __('Cyan'),
                'class' => 'btn-cyan',
            ],
            [
                'label' => __('Sky'),
                'class' => 'btn-sky',
            ],
            [
                'label' => __('Blue'),
                'class' => 'btn-blue',
            ],
            [
                'label' => __('Indigo'),
                'class' => 'btn-indigo',
            ],
            [
                'label' => __('Violet'),
                'class' => 'btn-violet',
            ],
            [
                'label' => __('Purple'),
                'class' => 'btn-purple',
            ],
            [
                'label' => __('Fuchsia'),
                'class' => 'btn-fuchsia',
            ],
            [
                'label' => __('Pink'),
                'class' => 'btn-pink',
            ],
            [
                'label' => __('Rose'),
                'class' => 'btn-rose',
            ],
            [
                'label' => __('Slate'),
                'class' => 'btn-slate',
            ],
            [
                'label' => __('Gray'),
                'class' => 'btn-gray',
            ],
            [
                'label' => __('Zinc'),
                'class' => 'btn-zinc',
            ],
            [
                'label' => __('Neutral'),
                'class' => 'btn-neutral',
            ],
            [
                'label' => __('Stone'),
                'class' => 'btn-stone',
            ],
        ]);
    }
};
