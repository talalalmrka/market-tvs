<?php

use App\Livewire\Components\DashboardPage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Buttons')] class extends DashboardPage
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
            [
                'label' => __('Info'),
                'class' => 'btn-info',
            ],
            [
                'label' => __('Success'),
                'class' => 'btn-success',
            ],
            [
                'label' => __('Warning'),
                'class' => 'btn-warning',
            ],
            [
                'label' => __('Error'),
                'class' => 'btn-error',
            ],
        ]);
    }

    #[Computed()]
    public function circleButtons()
    {
        return collect([
            [
                'label' => __('Primary'),
                'class' => 'btn-circle-primary',
            ],
            [
                'label' => __('Secondary'),
                'class' => 'btn-circle-secondary',
            ],
            [
                'label' => __('Light'),
                'class' => 'btn-circle-light',
            ],
            [
                'label' => __('Dark'),
                'class' => 'btn-circle-dark',
            ],
            [
                'label' => __('Red'),
                'class' => 'btn-circle-red',
            ],
            [
                'label' => __('Orange'),
                'class' => 'btn-circle-orange',
            ],
            [
                'label' => __('Amber'),
                'class' => 'btn-circle-amber',
            ],
            [
                'label' => __('Yellow'),
                'class' => 'btn-circle-yellow',
            ],
            [
                'label' => __('Lime'),
                'class' => 'btn-circle-lime',
            ],
            [
                'label' => __('Green'),
                'class' => 'btn-circle-green',
            ],
            [
                'label' => __('Emerald'),
                'class' => 'btn-circle-emerald',
            ],
            [
                'label' => __('Teal'),
                'class' => 'btn-circle-teal',
            ],
            [
                'label' => __('Cyan'),
                'class' => 'btn-circle-cyan',
            ],
            [
                'label' => __('Sky'),
                'class' => 'btn-circle-sky',
            ],
            [
                'label' => __('Blue'),
                'class' => 'btn-circle-blue',
            ],
            [
                'label' => __('Indigo'),
                'class' => 'btn-circle-indigo',
            ],
            [
                'label' => __('Violet'),
                'class' => 'btn-circle-violet',
            ],
            [
                'label' => __('Purple'),
                'class' => 'btn-circle-purple',
            ],
            [
                'label' => __('Fuchsia'),
                'class' => 'btn-circle-fuchsia',
            ],
            [
                'label' => __('Pink'),
                'class' => 'btn-circle-pink',
            ],
            [
                'label' => __('Rose'),
                'class' => 'btn-circle-rose',
            ],
            [
                'label' => __('Slate'),
                'class' => 'btn-circle-slate',
            ],
            [
                'label' => __('Gray'),
                'class' => 'btn-circle-gray',
            ],
            [
                'label' => __('Zinc'),
                'class' => 'btn-circle-zinc',
            ],
            [
                'label' => __('Neutral'),
                'class' => 'btn-circle-neutral',
            ],
            [
                'label' => __('Stone'),
                'class' => 'btn-circle-stone',
            ],
            [
                'label' => __('Info'),
                'class' => 'btn-circle-info',
            ],
            [
                'label' => __('Success'),
                'class' => 'btn-circle-success',
            ],
            [
                'label' => __('Warning'),
                'class' => 'btn-circle-warning',
            ],
            [
                'label' => __('Error'),
                'class' => 'btn-circle-error',
            ],
        ]);
    }
};
