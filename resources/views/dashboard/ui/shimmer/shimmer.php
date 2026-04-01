<?php

use App\Livewire\Components\DashboardPage;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

new #[Title('Shimmer')] class extends DashboardPage
{
    #[Computed()]
    public function shimmers(): Collection
    {
        $shimmers = [
            'w-full h-48',
            'w-80 aspect-video',
            'w-80 aspect-square',
            'w-80 aspect-16/9',
            'w-12 h-12 rounded-full',
        ];
        $animations = [
            'bg-shimmer',
            'bg-shimmer-slow',
            'bg-shimmer-diag',
        ];
        $classes = collect();
        collect($shimmers)->each(function ($shimmer) use (&$classes, $animations) {
            collect($animations)->each(function ($animation) use ($shimmer, &$classes) {
                $classes->push("$shimmer $animation");
            });
        });

        return $classes;
    }
};
