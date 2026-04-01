<?php

use App\SidebarItem;
use Livewire\Wireable;

test('sidebar item implements wireable and round trips through wireable methods', function () {
    $item = new SidebarItem(
        label: 'Settings',
        href: '/settings',
        icon: 'bi-gear',
        open: true,
        items: [
            ['label' => 'General'],
        ],
        attributes: [
            'wire:current' => 'active',
        ],
    );

    expect($item)->toBeInstanceOf(Wireable::class);

    $serialized = $item->toLivewire();
    $recreated = SidebarItem::fromLivewire($serialized);

    expect($recreated)
        ->label->toBe('Settings')
        ->href->toBe('/settings')
        ->icon->toBe('bi-gear')
        ->open->toBeTrue()
        ->items->toBe([['label' => 'General']])
        ->attributes->toBe(['wire:current' => 'active']);
});
