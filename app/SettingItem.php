<?php

namespace App;

use Livewire\Wireable;
use Illuminate\Support\Facades\Route;

class SettingItem implements Wireable
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $id,
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $href = null,
        public bool $navigate = true,
        public bool $hasView = true,
        public array $atts = [
            'wire:current.exact' => 'active',
        ]
    ) {}


    /**
     * get all items.
     * return Illuminate\Support\Collection
     */
    public static function all()
    {
        return collect([
            self::make([
                'id' => 'index',
                'label' => __('Manage settings'),
                'icon' => 'bi-gear-wide-connected',
            ]),
            self::make([
                'id' => 'broadcasting',
                'label' => __('Broadcasting settings'),
                'icon' => 'bi-send',
            ]),
            self::make([
                'id' => 'cache',
                'label' => __('Cache settings'),
                'icon' => 'bi-database',
            ]),
            self::make([
                'id' => 'database',
                'label' => __('Database settings'),
                'icon' => 'bi-database',
            ]),
            self::make([
                'id' => 'filesystems',
                'label' => __('Filesystems settings'),
                'icon' => 'bi-file',
            ]),
            self::make([
                'id' => 'fortify',
                'label' => __('Fortify settings'),
                'icon' => 'bi-person-gear',
                // 'href' => route('dashboard.settings.config', 'fortify'),
                'hasView' => false,
            ]),
            self::make([
                'id' => 'livewire',
                'label' => __('Livewire settings'),
                'icon' => 'bi-bolt',
                // 'href' => route('dashboard.settings.page', 'livewire'),
                'hasView' => false,
            ]),
            self::make([
                'id' => 'logging',
                'label' => __('Logging settings'),
                'icon' => 'bi-list',
                // 'href' => route('dashboard.settings.page', 'logging'),
                'hasView' => false,
            ]),
            self::make([
                'id' => 'mail',
                'label' => __('Mail settings'),
                'icon' => 'bi-envelope',
                // 'href' => route('dashboard.settings.page', 'mail'),
                'hasView' => false,
            ]),
            self::make([
                'id' => 'media-library',
                'label' => __('Media library settings'),
                'icon' => 'bi-image',
                // 'href' => route('dashboard.settings.page', 'media-library'),
                'hasView' => false,
            ]),
            self::make([
                'id' => 'permission',
                'label' => __('Permission settings'),
                'icon' => 'bi-key',
                // 'href' => route('dashboard.settings.page', 'permission'),
                'hasView' => false,
            ]),
            self::make([
                'id' => 'queue',
                'label' => __('Queue settings'),
                'icon' => 'bi-calendar',
                // 'href' => route('dashboard.settings.page', 'queue'),
                'hasView' => false,
            ]),
            self::make([
                'id' => 'reverb',
                'label' => __('Reverb settings'),
                'icon' => 'bi-send',
                // 'href' => route('dashboard.settings.page', 'reverb'),
                'hasView' => false,
            ]),
            self::make([
                'id' => 'santcum',
                'label' => __('Santcum settings'),
                'icon' => 'bi-link',
                // 'href' => route('dashboard.settings.page', 'santcum'),
                'hasView' => false,
            ]),
            self::make([
                'id' => 'services',
                'label' => __('Services settings'),
                'icon' => 'bi-server',
                // 'href' => route('dashboard.settings.page', 'services'),
                'hasView' => false,
            ]),
            self::make([
                'id' => 'session',
                'label' => __('Session settings'),
                'icon' => 'bi-database',
                // 'href' => route('dashboard.settings.page', 'session'),
                'hasView' => false,
            ]),
            self::make([
                'id' => 'tbumbnail',
                'label' => __('Thumbnail settings'),
                'icon' => 'bi-image',
                // 'href' => route('dashboard.settings.page', 'thumbnail'),
                'hasView' => false,
            ]),
        ]);
    }


    public static function make($data)
    {
        $id = data_get($data, 'id');
        $label = data_get($data, 'label');
        $icon = data_get($data, 'icon');
        $autoRoute = $id === 'index'
            ? 'dashboard.settings'
            : "dashboard.settings.{$id}";
        $autoHref = route_has($autoRoute)
            ? route($autoRoute)
            : null;
        $href = data_get($data, 'href', $autoHref);
        $navigate = data_get($data, 'navigate', true);
        $hasView = data_get($data, 'hasView', true);
        if (!$hasView) {
            $href = url("dashboard/settings/{$id}");
        }
        $atts = data_get($data, 'atts', [
            'wire:current.exact' => 'active',
        ]);
        return new static($id, $label, $icon, $href, $navigate, $hasView, $atts);
    }
    public static function fromLivewire($data)
    {
        return self::make($data);
    }
    public function toArray()
    {
        return [
            "id" => $this->id,
            "label" => $this->label,
            "icon" => $this->icon,
            "href" => $this->href,
            "navigate" => $this->navigate,
            "hasView" => $this->hasView,
            "atts" => $this->atts,
        ];
    }
    public function toLivewire()
    {
        return $this->toArray();
    }

    public function render($atts = [])
    {
        $this->atts = [
            ...$this->atts,
            ...$atts
        ];
        return view('components.nav-link', $this->toArray())->render();
    }

    public static function registerRoutes()
    {
        self::all()->each(function (SettingItem $item) {
            if ($item->hasView) {
                $uri = $item->id === 'index'
                    ? '/'
                    : "/{$item->id}";
                $view = "dashboard::settings.{$item->id}";
                $name = $item->id === 'index'
                    ? 'dashboard.settings'
                    : "dashboard.settings.{$item->id}";
                Route::livewire($uri, $view)->name($name);
            }
        });
    }

    public static function sidebar()
    {
        return self::all()->map(fn(SettingItem $item) => $item->render())->join('');
    }
}
