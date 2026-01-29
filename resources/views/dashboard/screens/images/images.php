<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public $colors = [
        'green',
        'blue',
        'orange',
        'red',
        'teal',
    ];

    #[Computed()]
    public function slides()
    {
        $ret = collect();
        collect(range(1, 3))->each(function ($slot) use (&$ret) {
            collect(range(1, 5))->each(function ($slide) use (&$ret, $slot) {
                // $color = collect($this->colors)->random();
                $color = $this->colors[$slide - 1];
                $ret->push("https://placehold.co/600x400/$color/white/png?text=Time Slot $slot Slide $slide");
            });
        });
        return $ret;
    }
};
